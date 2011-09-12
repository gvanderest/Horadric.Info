<?php
/**
 * Initialize Framework
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 */

define('EXO', TRUE);
if(!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); } 
 
// load configuration files (to keep the variables global, and not in a class)
require_once(__DIR__ . '/config/config.php');
ob_start();
session_start();
date_default_timezone_set(EXO_TIMEZONE);

// load configuration files
$routes = array();
require_once(EXO_PATH . '/configs.php');

// create the class autoloader
require_once(EXO_PATH . '/autoload.php');

// load error handler
require_once(EXO_PATH . '/handler.php');
