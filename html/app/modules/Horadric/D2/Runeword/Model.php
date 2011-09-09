<?php
/**
 * Horadric Runeword Model
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */

class Horadric_D2_Runeword_Model extends Exo_Model
{
    /**
     * Search runewords based on options
     * @param array $options (optional) array(
     *  'search' => 'jah', // searching for
     *  'search_field' => 'name' // field being searched
     * @return array of Horadric_D2_Runewords
     */
    public function get_runewords($options = array())
    {
        $defaults = array(
            'search' => '',
            'search_field' => 'all',
            'order_by' => array('name', 'asc')
        );
        $options = array_merge($defaults, $options);

        $wheres = array();
        $values = array();

        $filters = array(
            'runes' => 'runes LIKE :search',
            'name' => 'name LIKE :search',
            'effects' => 'effects LIKE :search'
        );

        if (!empty($options['search']))
        {
            $values[':search'] = '%' . $options['search'] . '%';

            if ($options['search_field'] == 'all')
            {
                foreach ($filters as $filter)
                {
                    $wheres[] = $filter;
                }
            } else {
                if (isset($filters[$options['search_field']]))
                {
                    $wheres[] = $filters[$options['search_field']];
                }
            }
        }

        $sql = '';
        $sql .= '
            SELECT *
            FROM runewords
        ';
        if (count($wheres) > 0)
        {
            $sql .= ' WHERE ' . implode(' AND ', $wheres);
        }
        $sql .= sprintf("
            ORDER BY %s %s
        ",
            $options['order_by'][0],
            $options['order_by'][1]
        );

        var_dump($sql);

        return $this->db->query_all($sql, $values);
    }
}
