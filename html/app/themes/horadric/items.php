<?php
/**
 * View a list of D3 Items
 */
$_title = 'Diablo 3 Items, Equipment, Weapons, Runestones and Gems Database';
$_description = 'A listing of all the available items that can be found in the world of Sanctuary';
include_once($this->theme_path . '/inc/header.php');
?>

<div id="view-items">
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
                <td><?= ucwords($item->type) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div> <!-- #view-items -->

<?php 
include_once($this->theme_path . '/inc/footer.php');
