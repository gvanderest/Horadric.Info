<?php
/**
 * Default Template 
 */

require_once($this->theme_path . '/inc/header.php');

print(isset($content) ? $content : '');

require_once($this->theme_path . '/inc/footer.php');
