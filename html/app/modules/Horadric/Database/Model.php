<?php
/**
 * Database Items/Monsters/Entities
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */
class Horadric_Database_Model extends Exo_Model
{
    public function get_item($id)
    {
        $items = $this->get_items();
        foreach ($items as $item)
        {
            if ($item->id == $id);
            {
                return $item;
            }
        }
        return NULL;
    }

    public function get_item_by_url($url)
    {
        $items = $this->get_items();
        foreach ($items as $item)
        {
            if ($item->url == $url);
            {
                return $item;
            }
        }
        return NULL;
    }

    /**
     * Add an item to the database
     * @param object $item
     * @return int id
     */
    public function add_item($item)
    {
        if (!isset($item->url))
        {
            $item->url = $this->db->get_unique_url($item->name, 'items');
        }
        return $this->db->insert('items', $item);
    }

    /**
     * Get an item by its diablo_id
     * @param string $diablo_id
     * @return object or NULL on failure
     */
    public function get_item_by_diablo_id($diablo_id)
    {
        $result = $this->get_items(array('diablo_id' => $diablo_id));
        return $result;
    }

    public function get_items($options = array())
    {
        $values = array();
        $options = array_merge(array(
            'order_by' => array('i.name asc'),
            'diablo_id' => NULL,
            'where' => array(),
            'amount' => NULL
        ), $options);

        if ($options['diablo_id'] !== NULL)
        {
            $options['where'][] = 'i.diablo_id = :diablo_id';
            $values[':diablo_id'] = $options['diablo_id'];
            $options['amount'] = 1;
        }

        $sql = "
            SELECT i.*
            FROM items i
        ";

        $sql = $this->db->get_select_sql($sql, $options);

        if ($options['amount'] == 1)
        {
            $result = $this->db->query_one($sql, $values);
        } else {
            $result = $this->db->query_all($sql, $values);
        }
        return $result;
    }
}
