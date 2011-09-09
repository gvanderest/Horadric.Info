<?php
/**
 * View a D3 Item
 */

header("Status: 404 Thread Not Found");
$title = 'Invalid Thread';
$description = '';

include_once($this->theme_path . '/inc/header.php');
?>

<h1>Cain Community Watcher</h1>
<h2>Invalid Thread</h2>
<p>The thread ID provided is not valid</p>
<p><a href="/cain">Return to Thread Listing</a></p>

<?php 
include_once($this->theme_path . '/inc/footer.php');
