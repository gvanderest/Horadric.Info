<?php
/**
 * Diablo 3 Item View
 */
$_title = $item->name;
$_description = $item->quality . ' ' . ucwords($item->quality) . ' ' . ucwords($item->type);
include_once($this->theme_path . '/inc/header.php');
?>

<div id="view-item">
    <h1>Items</h1>
    <p style="color: red;"><strong>THIS SECTION IS HEAVILY UNDER CONSTRUCTION AS THE GAME HAS NOT BEEN RELEASED OFFICIALLY AND SYSTEMS MAY CHANGE DRASTICALLY</strong></p>

<table class="database">
    <thead>
        <th>Item Name</th>
        <th>Quality</th>
        <th>Type</th>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><a class="quality-<?= $item->quality ?>" href="/item/<?= $item->url ?>"><?= $item->name ?></a></td>
                <td><?= ucwords($item->quality) ?></td>
                <?php if ($item->type == 'weapon'): ?>
                    <td>Weapon - <?= (in_array($item->weapon_type, array('mace', 'sword', 'axe'))) ? ($item->hands . 'H') : '' ?> <?= ucwords($item->weapon_type) ?></td>
                <?php elseif ($item->type == 'armor'): ?>
                    <td>Armor - <?= ucwords($item->armor_type) ?></td>
                <?php else: ?>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div> <!-- #view-items -->

<?php 
include_once($this->theme_path . '/inc/footer.php');
