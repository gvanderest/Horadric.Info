<?php
/**
 * Diablo 3 Item View
 */
$_title = $item->name;
$_description = ucwords($item->quality) . ' ' . ucwords($item->subtype) . ' ' . ucwords($item->type);
include_once($this->theme_path . '/inc/header.php');

$_fields = array(
    'name' => 'Name',
    'quality' => 'Quality',
    'flavor' => 'Flavor Text',
    'gold' => 'Gold Value', 
    'ilvl' => 'Item Level',
    'clvl' => 'Character Level',
    'sockets_max' => 'Maximum Sockets'
    );
?>

<div id="view-item">
    <h1><?= $item->name ?></h1>

<table class="database">
<?php foreach ($item as $_field => $_value): ?>
    <tr>
        <th><?= ucwords($_field) ?></th>
        <td><?= ucwords($_value) ?></td>
    </tr>
<?php endforeach; ?>
</table>

</div> <!-- #view-item -->

<?php 
include_once($this->theme_path . '/inc/footer.php');
