<?php
/**
 * Invalid News Story
 */

header("Status: 404 Story Not Found");
$title = 'Invalid Thread';
$description = '';

include_once($this->theme_path . '/inc/header.php');
?>

<h1>News</h1>
<h2>Invalid Story</h2>
<p>The story ID provided is not valid</p>
<p><a href="/news">Return to News Listing</a></p>

<?php 
include_once($this->theme_path . '/inc/footer.php');
