<?php
/**
 * Database Items/Monsters/Entities
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */
class Horadric_Database_Model
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

    public function get_items()
    {
        $raw_items = array(
            array('url' => 'flesh-tearer', 'type' => 'weapon', 'name' => 'Flesh Tearer', 'quality' => 'unique', 'weapon_type' => 'axe', 'hands' => 1),
            array('url' => 'etrayu', 'type' => 'weapon', 'name' => 'Etrayu', 'quality' => 'unique', 'weapon_type' => 'bow', 'hands' => 2),
            array('url' => 'demon-machine', 'type' => 'weapon', 'name' => 'Demon Machine', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('url' => 'manticore', 'type' => 'weapon', 'name' => 'Manticore', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('url' => 'starspine', 'type' => 'weapon', 'name' => 'Starspine', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'Bakkan Caster', 'url' => 'bakkan-caster', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'Crossfire', 'url' => 'crossfire', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'Peacemaker', 'url' => 'peacemaker', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'The Riveter', 'url' => 'the-riveter', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'Shock Bolt Launcher', 'url' => 'shock-bolt-launcher', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'Echoing Fury', 'url' => 'echoing-fury', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1),
            array('name' => 'Odyn Son', 'url' => 'odyn-son', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1),
            array('name' => 'Neanderthal', 'url' => 'neanderthal', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1),
            array('name' => 'Devastator', 'url' => 'devastator', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1),
            array('name' => 'Nutcracker', 'url' => 'nutcracker', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1),
            array('name' => 'Dai Bachi', 'url' => 'dai-bachi', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Telranden\'s Hand', 'url' => 'telrandens-hand', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Nailbiter', 'url' => 'nailbiter', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Earthshatter', 'url' => 'earthshatter', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Wooden Leg', 'url' => 'wooden-leg', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'The Incubuster', 'url' => 'the-incubuster', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Crushbane', 'url' => 'crushbane', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Sister Sledge', 'url' => 'sister-sledge', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('name' => 'Boneshatter', 'url' => 'boneshatter', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('name' => 'Groundpounder', 'url' => 'groundpounder', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('name' => 'Rockbreaker', 'url' => 'rockbreaker', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('name' => 'Cataclysm', 'url' => 'cataclysm', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('name' => 'Overfiend', 'url' => 'overfiend', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('url' => 'leather-hood', 'type' => 'armor', 'name' => 'Leather Hood', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => 'skull-cap', 'type' => 'armor', 'name' => 'Skull Cap', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => 'coif', 'type' => 'armor', 'name' => 'Coif', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => 'scale-helmet', 'type' => 'armor', 'name' => 'Scale Helmet', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => 'arming-cap', 'type' => 'armor', 'name' => 'Arming Cap', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => 'plated-helm', 'type' => 'armor', 'name' => 'Plated Helm', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => '', 'type' => 'armor', 'name' => 'Plated Helm', 'quality' => 'common', 'armor_type' => 'helm')
        );  

        $items = array();
        foreach ($raw_items as $index => $raw_item)
        {
            $item = (object)$raw_item;
            $item->id = $index + 1;
            $items[] = $item;
        }

        return $items;
    }
}
