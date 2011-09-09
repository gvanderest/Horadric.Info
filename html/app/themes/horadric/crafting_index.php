<?php
/**
 * Content Page Regarding Crafting in Diablo 3
 * @author Guillaume VanderEst <gvanderest@netshiftmedia.com>
 * @package horadric
 */

$title = 'Crafting and Artisans';
$description = 'Diablo 3 will feature a system for creating gear called crafting via artisan NPCs found during their adventures.  The system will provide players with a method of obtaining equipment and items without having to level a profession on their character, but rather provide reagents and supplies to an artisan to further their craft.';

include_once($this->theme_path . '/inc/header.php');
?>

<h1><?= $title ?></h1>
<p><img src="/assets/images/blacksmith.png" alt="Blacksmith Artisan" style="float: right; width: 200px; height: 350px; margin: 0 0 15px 25px;" />Diablo 3 will feature a system for creating gear called crafting via artisan NPCs found during their adventures.  The system will provide players with a method of obtaining equipment and items without having to level a profession on their character, but rather provide reagents and supplies to an artisan to further their craft. At this time, the only artisan fully described has been the <strong>blacksmith</strong>, who is able to craft armors and weapons for the player, as well as add gem sockets to equipment that may not already have sockets.</p>
<p>In order to make use of these artisans, supplies or gold must be provided to them, which are gathered by killing creatures through the game's acts and making use of a <em>Horadric Chest</em> that will allow magical and rare items to be broken down into supplies without having to return to town.</p>

<p style="text-align: center;"><iframe width="320" height="240" src="http://www.youtube.com/embed/mlTu2yCdkCI" frameborder="0" allowfullscreen></iframe></p>

<p>Additional artisans may include a <strong>jeweler</strong>, able to create amulets, rings and combine gems, as well as a <strong>mystic</strong> who can enchant equipment, such as weapons to do additional damage to enemies.</p>

<?php 
include_once($this->theme_path . '/inc/footer.php');
