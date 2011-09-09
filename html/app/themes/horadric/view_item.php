<?php
/**
 * View a D3 Item
 */

$title = $item->name;
$description = 'The Diablo 3 ' . ucwords($item->quality) . ' ' . ucwords($item->type) . ' ' . $item->name;

include_once($this->theme_path . '/inc/header.php');
?>

<p><a href="/item">Return to Items</a></p>
<h1><?= link_to('d3item', array('item_id' => $item->url), $item->name); ?></h1>
<div class="item quality-<?= $item->quality ?>">
    <table>
        <?php foreach ($item as $field => $value): ?>
        <tr>
            <th><?= $field ?></th><td><?= $value ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php if ($item->type == 'weapon'): ?>
<h2>This is a Weapon</h2>
<p>Weapon Class: <?= $item->hands == 2 ? 'Two-Handed' : 'One-Handed' ?> <?= $item->weapon_type ?></p>
<p>DPS: <?= ($item->damage_min + $item->damage_max) / 2 * $item->speed; ?></p>
<?php endif; ?>

<?php if ($basis !== NULL): ?>
This item is based on <a href="/item/<?= $basis->url ?>"><?= $basis->name ?></a>
<?php endif; ?>

<?php $effects = $item->effects; ?>
<?php if (count($effects) > 0): ?>
<h2>Effects</h2>
<table>
    <tr>
        <th>Description</th>
        <th>Field</th>
        <th>Value Type</th>
        <th>Min</th>
        <th>Max</th>
    </tr>
    <?php foreach ($effects as $effect): ?>
    <tr>
        <td><?= $effect->description ?></td>
        <td><?= $effect->type ?></td>
        <td><?= $effect->value_type ?></td>
        <td><?= $effect->value_min ?></td>
        <td><?= $effect->value_max ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<?php 
include_once($this->theme_path . '/inc/footer.php');
