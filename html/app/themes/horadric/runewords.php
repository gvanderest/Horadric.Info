<?php
/**
 * Output a list of runewords, as well as search form
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */

require_once($this->theme_path . '/inc/header.php');
?>

<table>
    <tr>
        <th>Name</th>
        <th>Equipment</th>
        <th>Rune Word</th>
        <th>Effects</th>
    </tr>
    <?php foreach ($runewords as $runeword): ?>
    <?php $runes = explode(',', $runeword->runes); ?>
    <?php foreach ($runes as $index => $rune) { $runes[$index] = ucwords($rune); } ?>
    <tr>
        <td><?= $runeword->name ?></td>
        <td><?= count($runes) ?> Socket <?= $runeword->items ?></td>
        <td><?= implode(' + ', $runes) ?></td>
        <td><?= nl2br($runeword->effects) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php
require_once($this->theme_path . '/inc/footer.php');
