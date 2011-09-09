<?php
/**
 * View a D3 Item
 */

$tmp_class_names = array();
foreach ($classes as $class)
{
    $tmp_class_names[] = $class->name;
}

$title = 'Player Classes';
$description = 'A listing of the classes available in Diablo 3.  Including ' . implode(', ', $tmp_class_names) . '.';

include_once($this->theme_path . '/inc/header.php');
?>

<h1><?= $title ?></h1>
<p>Below is a listing of the player classes that are available in Diablo 3:</p>
<table class="database">
    <thead>
        <th>Class Name</th>
        <th>Resource</th>
    </thead>
    <tbody>
        <?php foreach ($classes as $class): ?>
            <tr>
                <td><a href="/class/<?= $class->url ?>"><?= $class->name ?></a></td>
                <td><?= $class->resource ?></td>
        <?php endforeach; ?>
    </tbody>
</table>

<?php 
include_once($this->theme_path . '/inc/footer.php');
