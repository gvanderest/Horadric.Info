<?php
/**
 * Database Connection
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package exo
 */

class Exo_Database_Connection extends PDO
{
    /**
     * Get a unique URL based on a table
     * @param string $input the input string to urlify
     * @param string $table
     * @param string $url_field (optional) the url field from the table
     */
    public function get_unique_url($input, $table, $url_field = 'url')
    {
        $url = strtolower(trim($input));
        $url = preg_replace('/[^a-z0-9]+/', '-', $url);
        $url = preg_replace('/-+/', '-', $url);
        $url = trim($url, '-');

        $pattern = $url . '%';

        $sql = "
            SELECT url
            FROM " . $table . "
            WHERE " . $url_field . " LIKE :pattern
        ";
        $values = array(':pattern' => $pattern);

        // existing urls
        $existing = array();
        foreach ($this->query_all($sql, $values) as $result)
        {
            $existing[] = $result->url;
        }

        // test urls to see if this is unique enough until it succeeds
        for ($x = 0; TRUE; $x++)
        {
            $temp = $url . ($x == 0 ? '' : '-' . $x);
            if (!in_array($temp, $existing))
            {
                break;
            }
        }
        return $temp;
    }


    /**
     * Instantiate the connection
     * @param string $db (optional) identifier of database from /exo/config/databases.php
     * @return void
     */
    public function __construct($db = EXO_DEFAULT_DATABASE)
    {
        global $databases;
        $entry = $databases[$db];
        // todo error check if database connection doesn't exist
        $dsn = sprintf('%s:dbname=%s;host=%s', $entry['engine'], $entry['database'], $entry['host']);
        parent::__construct($dsn, $entry['username'], $entry['password']);
    }

    /**
     * Get the ID of the last inserted row
     * @param void
     * @return int last_id or FALSE
     */
    public function get_insert_id()
    {
        return $this->lastInsertId();
    }

    /**
     * Query quickly
     * @param string $sql
     * @param array $values (optional)
     * @return object or NULL if nothing returned
     */
    public function query($sql, $values = array())
    {
        $query = $this->prepare($sql);
        $result = $query->execute($values);
        return $result;
    }

    /**
     * Update record(s)
     * @param string $table
     * @param mixed $id if integer, update one record, if array use IN_LIST
     * @param mixed $data object or array with matching keys/attributes
     * @return bool
     */
    public function update($table, $id, $data, $options = array())
    {
        $defaults = array(
            'id_field' => 'id'
        );
        $options = array_merge($defaults, $options);

        $data = (array)$data;
        
        $values = array();
        $updates = array();

        foreach ($data as $field => $value)
        {
            $updates = sprintf('%s = :%s', $field, $field);
            if (substr($field, 5) == 'date_' && is_numeric($value) && $value !== NULL)
            {
                $values[':' . $field] = date('Y-m-d H:i:s', $value);
            } else {
                $values[':' . $field] = $value;
            }
        }

        if (!is_array($id)) 
        { 
            $values[':EXO_SQL_UPDATE_ID'] = $id; 
        } else {
            foreach ($id as $index => $row)
            {
                $id[$index] = (int)$row;
            }   
        }

        $sql = sprintf('UPDATE %s SET %s WHERE %s %s',
            $table,
            implode(',', $updates),
            $options['id_field'],
            (is_array($id) ? ('IN (' . implode(',', $id) . ')') : '= :EXO_SQL_UPDATE_ID')
        );

        $query = $this->prepare($sql);
        $result = $query->execute($values);
        if ($result)
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Insert a record into the respective table
     * @param string $table
     * @param mixed $data object or array with matching keys/attributes
     * @return int id or FALSE on failure
     */
    public function insert($table, $data, $options = array())
    {
        $data = (array)$data;
        
        $values = array();
        $fields = array();

        foreach ($data as $field => $value)
        {
            $fields[] = $field;
            if (substr($field, 5) == 'date_' && is_numeric($value) && $value !== NULL)
            {
                $values[':' . $field] = date('Y-m-d H:i:s', $value);
            } else {
                $values[':' . $field] = $value;
            }
        }

        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(',', $fields),
            implode(',', array_keys($values))
        );

        $query = $this->prepare($sql);
        $result = $query->execute($values);
        if ($result)
        {
            return $this->get_insert_id();
        }
        return FALSE;
    }

    /**
     * Build me a simple filter around a select query
     * @param string $sql (basic select)
     * @param array $options array(
     *  'where' => array(),
     *  'order_by' => array(),
     *  'amount' => 100,
     *  'offset' => 10
     * )
     */

    public function get_select_sql($sql, $options = array())
    {
        $defaults = array(
            'where' => NULL,
            'order_by' => NULL,
            'amount' => NULL,
            'offset' => 0
        );
        $options = array_merge($defaults, $options);

        $parts = array($sql);

        // if there are where clauses, add em
        if ($options['where'] !== NULL && count($options['where']) > 0)
        {
            $parts[] = 'WHERE (' . implode(') AND (', $options['where']) . ')';
        }

        // if there order by's, add em
        if ($options['order_by'] !== NULL & count($options['order_by']) > 0)
        {
            $parts[] = 'ORDER BY ' . implode(',', $options['order_by']);
        }

        // if there is an offset given, default the amount to being 1 if not provided, because it NEEDS to be provided to continue
        if ($options['offset'] !== NULL && $options['amount'] === NULL)
        {
            $options['amount'] = 1;
        }   

        if ($options['amount'] !== NULL)
        {
            $parts[] = sprintf('LIMIT %d, %d', $options['offset'], $options['amount']);
        }

        return implode(' ', $parts);
    }

    /**
     * Get a single result from a query
     * @param string $sql
     * @param array $values (optional)
     * @param array $options (optional) array(
     *  'date' => array('test_date'), // names of fields which are date type
     * )
     * @return object or NULL if nothing returned
     */
    public function query_one($sql, $values = array(), $options = array())
    {
        $defaults = array(
            'date' => array()
        );
        $options = array_merge($defaults, $options);

        $query = $this->prepare($sql);
        $result = $query->execute($values);
        if ($result)
        {
            $row = $query->fetch(PDO::FETCH_OBJ);
            if ($row)
            {
                foreach ($row as $field => $value)
                {
                    if (substr($field, 0, 5) == 'date_' || in_array($field, $options['date']))
                    {
                        $row->$field = strtotime($value);   
                    }
                }
            }
            return $row;
        }
        return NULL;
    }

    /**
     * Get all of the results from a query
     * @param string $sql
     * @param array $values (optional)
     * @param array $options (optional) array(
     *  'date' => array('test_date'), // names of fields which are date type
     * )
     * @return array
     */
    public function query_all($sql, $values = array(), $options = array())
    {
        $defaults = array(
            'date' => array()
        );
        $options = array_merge($defaults, $options);

        $query = $this->prepare($sql);
        $result = $query->execute($values);
        if ($result)
        {
            $rows = $query->fetchAll(PDO::FETCH_OBJ); 
            if ($rows)
            {
                foreach ($rows as $index => $row)
                {
                    foreach ($row as $field => $value)
                    {
                        if (substr($field, 0, 5) == 'date_' || in_array($field, $options['date']))
                        {
                            $rows[$index]->$field = strtotime($value);   
                        }
                    }
                }
            }
            return $rows;
        }
        return array();
    }
}
