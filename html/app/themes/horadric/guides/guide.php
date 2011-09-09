<?php
/**
 * Guides Display
 */

$_title = $guide->title;
$_description = htmlentities(substr($guide->body, 0, 200));

require_once($this->theme_path . '/inc/header.php');
?>

<h1><?= $_title ?></h1>
<p class="back"><a href="/guides">Return to Guide Categories</a></p>

<?php if (count($guides) == 0): ?>
    <p>There are currently no guides to display in this category</p>
<?php else: ?>
    <ul>
        <?php foreach ($guides as $guide): ?>
        <li><a href="/guide/<?= $guide->url ?>"><?= $guide->name ?></a></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php
require_once($this->theme_path . '/inc/footer.php');
