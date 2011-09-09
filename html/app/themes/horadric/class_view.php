<?php
/**
 * Display a class
 * @author Guillaume VanderEst <gvanderest@netshiftmedia.com>
 */

$title = $class->name;
$description = htmlentities(substr($class->description, 1000));

include_once($this->theme_path . '/inc/header.php');
?>

<div id="class-flash">
    <object data="/assets/flash/<?= $class->url ?>-male.swf">
        <param name="wmode" value="transparent" />
    </object>
</div> <!-- #class-flash -->

<h1><?= $title ?></h1>
<p><a href="/classes">Return to Class Listing</a></p>
<?= $class->description ?>
<h2>Skills</h2>
<?php if (count($skills) == 0): ?>
<p>There are currently no skills to be displayed.</p>
<?php else: ?>
<ul>
    <?php foreach ($skills as $skill): ?>
    <li><a href="/skill/<?= $skill->url ?>"><?= $skill->name ?></a></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php 
include_once($this->theme_path . '/inc/footer.php');
