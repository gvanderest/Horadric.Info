<?php
/**
 * Listing of Titles
 */
$_title = 'Player Titles';
$_description = 'A list of player titles available when certain conditions/achievements have been reached.';
?>
<?php require_once($this->theme_path . '/inc/header.php'); ?>

<h1>Player Titles</h1>
<p>The following player titles are available when certain conditions have been met.</p>

<table id="player-titles" class="database">
    <thead>
        <tr>
            <th>Source</th>
            <th>Male Title</th>
            <th>Female Title</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($titles as $title): ?>
        <tr>
            <td><?= $title->source ?></td>
            <?php if ($title->male == $title->female): ?>
                <td colspan="2"><?= $title->male ?></td>
            <?php else: ?>
                <td><?= $title->male ?></td>
                <td><?= $title->female ?></td>
            <?php endif; ?>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php require_once($this->theme_path . '/inc/footer.php'); ?>
