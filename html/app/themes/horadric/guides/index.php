<?php
/**
 * Guides Category Listing
 */

$_title = 'Diablo 3 Guides';

require_once($this->theme_path . '/inc/header.php');
?>

<h1>Diablo 3 Guides</h1>

<p>In your travels through Sanctuary, many a foe will step to your might.  Will you have the tools and knowledge to strike them down?</p>

<h2>Guide Categories</h2>
<?php if (count($categories) == 0): ?>
    <p>There are currently no guide categories to display</p>
<?php else: ?>
    <ul>
        <?php foreach ($categories as $category): ?>
        <li><a href="/guides/<?= $category->url ?>"><?= $category->name ?></a> (<?= count($category->guide_count) ?>)</li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p>If you wish to submit a guide for Diablo 3, please email <a href="mailto:wrack@horadric.info">wrack@horadric.info</a></p>

<?php
require_once($this->theme_path . '/inc/footer.php');
