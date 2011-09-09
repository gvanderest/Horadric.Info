<?php
/**
 * View a list of D3 Items
 */
$_title = 'Diablo 3 Items, Equipment, Weapons, Runestones and Gems Database';
include_once($this->theme_path . '/inc/header.php');
?>

<div id="view-items">
    <h1>Items</h1>
    <p style="color: red;"><strong>THIS SECTION IS HEAVILY UNDER CONSTRUCTION AS THE GAME HAS NOT BEEN RELEASED OFFICIALLY AND SYSTEMS MAY CHANGE DRASTICALLY</strong></p>
    <table>
        <thead>
            <tr>
                <th colspan="2" class="asc">Name</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <?php $sprite_path = SITE_PATH . '/assets/sprites/' . $item->sprite . '.png'; ?>
        <?php $sprite_url = SITE_URL . '/assets/sprites/' . $item->sprite . '.png'; ?>
            <tr>
                <td class="sprite"><?php if (file_exists($sprite_path)): ?>'<a href="http://horadric.info/item/<?= $item->url ?>"><img src="<?= $sprite_url ?>" alt="<?= $item->name ?>" /></a><?php endif; ?></td>
                <td><?= link_to('d3item', array('item_id' => $item->url), array('text' => $item->name, 'classes' => array('quality-' . $item->quality))); ?></td>
                <td><?= ucwords($item->type) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div> <!-- #view-items -->

<?php 
include_once($this->theme_path . '/inc/footer.php');
