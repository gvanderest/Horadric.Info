<?php
/**
 * Horadric_Scraper_Model
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */
class Horadric_Scraper_Model extends Exo_Model
{
    public function add_scrape($data)
    {
        if (!isset($data->date_created)) { $data->date_created = date('Y-m-d H:i:s'); }
        if (!isset($data->date_updated)) { $data->date_updated = date('Y-m-d H:i:s'); }

        return $this->db->insert('scrapes', $data);
    }

    public function get_basic_scrapes($options = array())
    {
        $defaults = array(
            'scraped' => FALSE
        );
        $options = array_merge($defaults, $options);
        return $this->get_scrapes($options);
    }

    public function get_scrape_by_hash($hash, $options = array())
    {
        return $this->get_scrape(array_merge(array('hash' => $hash), $options));
    }

    public function get_scrape($options = array())
    {
        $defaults = array(
            'amount' => 1
        );
        $options = array_merge($defaults, $options);

        return $this->get_scrapes($options);
    }

    public function get_scrapes($options = array())
    {
        $defaults = array(
            'hash' => NULL,
            'basic' => NULL,
            'scrape_id' => NULL
        );
        $options = array_merge($defaults, $options);

        $values = array();
        $wheres = array();
        $orders = array();

        if ($options['scrape_id'] !== NULL)
        {
            $wheres[] = 's.id = :id';
            $values[':id'] = $options['scrape_id'];
        }

        if ($options['scraped'] !== NULL)
        {
            $wheres[] = 's.scraped = :scraped';
            $values[':scraped'] = $options['basic'] ? 1 : 0;
        }

        if ($options['hash'] !== NULL)
        {
            $wheres[] = 's.hash = :hash';
            $values[':hash'] = $options['hash'];
        }

        $sql = '
            SELECT *
            FROM scrapes s
        ';
 
        $sql = $this->db->get_select_sql($sql, array(
            'where' => $wheres,
            'order_by' => $orders,
            'amount' => $options['amount'],
            'offset' => $options['offset']
        ));

        var_dump($sql);
        var_dump($values);

        if ($options['amount'] == 1)
        {
            return $this->db->query_one($sql, $values);
        }
        return $this->db->query_all($sql, $values);
    }

    public function get_source($options = array())
    {
        $defaults = array(
            'amount' => 1
        );
        $options = array_merge($defaults, $options);

        return $this->get_sources($options);
    }

    public function get_sources($options = array())
    {
        $defaults = array(
            'amount' => NULL,
            'active' => NULL
        );
        $options = array_merge($defaults, $options);

        $values = array();
        $query = array();
        $wheres = array();

        $query[] = "
            SELECT s.*
            FROM scrape_sources s
        ";

        // get active ones only
        if ($options['active'])
        {
            $wheres[] = 's.active = :active';
            $values[':active'] = 1;
        }

        if ($options['amount'] !== NULL)
        {
            $query[] = 'LIMIT ' . $options['amount'];
        }

        if (count($wheres) > 0) { $query[] = 'WHERE (' . implode(') AND (', $wheres) . ')'; }
        $sql = implode(' ', $query);

        if ($options['amount'] == 1)
        {
            return $this->db->query_one($sql, $values);
        }
        return $this->db->query->all($sql, $values);
    }
}
