<?php
/**
 * View a D3 Item in JSON format
 */

if (!$item)
{
    exit();
}

$effects = $item->effects;

$output = $item->_data;
$output['effects'] = array();

// if it's a weapon, the type is complex
if ($item->type == 'weapon')
{
    $output['type_string'] = '';
    if ($item->hands == 2 && $item->weapon_type != 'crossbow' && $item->weapon_type != 'bow')
    {
        $output['type_string'] = 'Two-Handed ';
    }
    $output['type_string'] .= ucwords($item->weapon_type);

} elseif ($item->type == 'armor') {
    
    $output['type_string'] = ucwords($item->slot . ' Armor');

} else {

    $output['type_string'] = ucwords($item->type);
}

if (count($effects) > 0)
{
    foreach ($effects as $effect)
    {
        $output['effects'][] = $effect->description;
    }
}
print(json_encode($output));
