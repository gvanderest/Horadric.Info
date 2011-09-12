<?php
/**
 * Read Item STL File
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 */

class Item_Reader
{
    public function get_items_data($filename = NULL)
    {
        if ($filename === NULL)
        {
            $filenames = array(
                '../data/mpq/Items_Armor.gam',
                '../data/mpq/Items_Legendary.gam',
                '../data/mpq/Items_Legendary_Other.gam',
                '../data/mpq/Items_Legendary_Weapons.gam',
                '../data/mpq/Items_Other.gam',
                '../data/mpq/Items_Quests_Beta.gam',
                '../data/mpq/Items_Weapons.gam'
            );

            $data = array();
            foreach ($filenames as $filename)
            {
                $data = array_merge($data, $this->get_items_data($filename));
            }
            return $data;
        }

        $fh = fopen($filename, 'r');
        fseek($fh, 0x3ac);

        $output = array();
        $y = 0;

        while (!feof($fh))
        {
            $item = new stdClass;

            $read = fread($fh, 0x100);
            if (trim(substr($read, 0, 1)) == '') { break; }
            
            $y++;

            $item->diablo_id = $read;
            /*
            var_dump("===============================");
            var_dump($item->id);
            var_dump("===============================");
            */
            $fields = array(
                //0 => array('subtype_maybe', 'int'),
                //2 => array('type_maybe_or_quality', 'int'),
                4 => array('ilvl', 'int'),
                //8 => array('sockets_max', 'int'),
                10 => array('gold', 'int'),
                12 => array('clvl', 'int'),
                //13 => array('something1', 'int'),
                //14 => array('something2', 'int')
            );

            for ($x = 0; $x < 292 && !feof($fh); $x++)
            {
                $pos = ftell($fh);
                $raw = fread($fh, 4);

                if (empty($raw)) { break; }

                $char = unpack('C', $raw);
                $short = unpack('S', $raw);
                $int = unpack('i', $raw);
                $float = unpack('f', $raw);
                $long = unpack('L', $raw);
                if ($y < 10 && $x < 20)
                /*printf("(@%s:%d) %sint: %s, float: %s\n", 
                    dechex($pos), 
                    $x, 
                    (isset($fields[$x]) ? '[' . $fields[$x][0] . '] ' : ''), 
                    $int[1], 
                    (float)$float[1]
                );*/

                if (isset($fields[$x]))
                {
                    $field = $fields[$x];
                    $field_name = $field[0];
                    $field_type = $field[1];
                    $val = $$field_type;
                    $item->$field_name = $val[1];
                }
            }
            if (!isset($count)) { $count = 0; }
            
            foreach ($item as $key => $value)
            {
                $item->$key = trim($value);
            }
            $output[] = $item;
        }

        return $output;
    }

    public function get_items()
    {
        $fh = fopen('../data/mpq/Items.stl', 'r');
        fseek($fh, 0x002cbd0, SEEK_SET);
        $null_count = 0;
        $char_count = 0;
        $part = 0;

        $item_id = '';
        $item_name = '';
        $item_notes = '';

        $items = array();

        $buffer = '';
        $item_part = 0;


        while (!feof($fh))
        {
            $read = fread($fh, 1024);

            // for each character
            for ($x = 0; $x < strlen($read); $x++)
            {
                $char = substr($read, $x, 1);

                // there are null characters coming up..
                if (ord($char) == 0) 
                { 
                    // if null_count is zero, we just ended a string
                    if ($null_count == 0)
                    {
                        // if there's an underscore, it's an ID; otherwise, it's the next part
                        if (strpos($buffer, '_') !== FALSE || substr($buffer, 0, 5) == 'Glyph')
                        {
                            if (!empty($item_id))
                            {
                                // append item
                                $item = new stdClass;
                                $item->diablo_id = $item_id;
                                $item->name = $item_name;
                                $item->notes = $item_notes;
                                $item->quality = 'inferior';

                                if (preg_match('/Unique/i', $item->diablo_id))
                                {
                                    $item->quality = 'legendary';
                                } elseif (preg_match('/Rare/i', $item->diablo_id)) {
                                    $item->quality = 'rare';
                                }


                                foreach ($item as $key => $value)
                                {
                                    $item->$key = trim($value);
                                }

                                $items[] = $item;
                    
                                // append item and start again
                                $item_part = 0;

                                $item_id = '';
                                $item_name = '';
                                $item_notes = '';
                            }

                        } else {
                            if ($char_count > 0)
                            {
                                $item_part++;
                                if ($item_part > 2) { $item_part = 0; }
                            }
                        }
            
                        // store the information appropriately            
                        switch ($item_part)
                        {
                            case 0: $item_id = $buffer; break;
                            case 1: $item_name = $buffer; break;
                            case 2: $item_notes = $buffer; break;
                        }
                    }

                    $null_count++; 

                    continue; 
                }
                elseif ($null_count > 0) 
                { 
                    $buffer = '';
                    $null_count = 0;
                    $part += 1;
                    $char_count++;
                }
                $buffer .= $char;
            }
        }

        // append final
        $item = new stdClass;
        $item->diablo_id = $item_id;
        $item->name = $item_name;
        $item->notes = $item_notes;
        foreach ($item as $key => $value)
        {
            $item->$key = trim($value);
        }
        $items[] = $item;
        return $items;
    }
}

$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . '/../html/';
require_once('../html/exo/init.php');

$model = new Horadric_Database_Model();
$reader = new Item_Reader();

// get the names and notes of items
$datas = $reader->get_items();
var_dump(count($datas));
foreach ($datas as $row)
{
    $item = $model->get_item_by_diablo_id($row->diablo_id);

    // if the item doesn't exist, create it
    if (!$item)
    {
        $model->add_item($row);
    }
}
