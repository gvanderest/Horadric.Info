<?php
/**
 * Forum or thread not found
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 */

header("Status: 404 Not Found");

$_title = 'Forum or Thread Not Found';
$_description = 'The requested forum or thread could not be located';
require_once($this->theme_path . '/forum/inc/header.php');
?>

<h1><?= $_title ?></h1>
<p><?= $_description ?></p>

<?php
require_once($this->theme_path . '/forum/inc/footer.php');
