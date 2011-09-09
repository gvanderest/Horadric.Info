<?php
/**
 * Guide Model
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 */
class Horadric_Guide_Model extends Exo_Model
{
    public function get_guides($options = array())
    {
        $defaults = array(
            'category_id' => NULL,
            'order_by' => NULL
        );
        $options = array_merge($defaults, $options);

        $values = array();        

        $sql = "
            SELECT g.*
            FROM guides
        ";

        $results = $this->db->query_all($sql, $values);
        foreach ($results as $index => $row)
        {
            $results[$index]->date_created = strtotime($row->date_created);
            $results[$index]->date_updated = strtotime($row->date_updated);
        }
        
        return $results;
    }

    /**
     * Synonym for get_guide_categories, but suggests singular
     * @param array $options
     * @return mixed see get_guide_categories
     */
    public function get_guide_category($options = array())
    {
        $defaults = array(
            'amount' => 1
        );
        $options = array_merge($defaults, $options);

        return $this->get_guide_categories($options);
    }

    public function get_guide_categories($options = array())
    {
        $defaults = array(
            'start' => 0,
            'url' => NULL,
            'amount' => NULL
        );
        $options = array_merge($defaults, $options);

        $values = array();
        $sql = "
            SELECT c.*,
                gcg.guide_count
            FROM guide_categories c
            LEFT JOIN (
                SELECT gcg.category_id,
                    COUNT(gcg.guide_id) AS guide_count
                FROM guide_category_guides gcg
                GROUP BY gcg.category_id
            ) gcg ON c.id = gcg.category_id
        ";

        if ($options['url'] != NULL)
        {
            $values[':url'] = $options['url'];
            $sql .= ' WHERE c.url = :url ';
        }

        $sql .= " ORDER BY c.rank ASC, c.name ASC ";

        if ($options['amount'] != NULL)
        {
            $sql .= ' LIMIT ' . $options['amount'];
        }

        if ($options['amount'] == 1)
        {
            $result = $this->db->query_one($sql, $values);
            return $result;
        }
        return $this->db->query_all($sql, $values);
    }
}
