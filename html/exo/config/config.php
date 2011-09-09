<?php
/**
 * ExoSkeleton Base Configuration File
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */
if (!defined('EXO')) { exit('This file cannot be accessed directly.'); }

/**
 * If the framework is running in a subfolder, this will try to detect the subfolder.. 
 */
$path = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
define('EXO_SUBFOLDER', $path == '/' ? '' : $path);
unset($path);

/** 
 * Constants that don't change much
 */
define('EXO_BASE_PATH', $_SERVER['DOCUMENT_ROOT']);
define('EXO_BASE_URL', EXO_SUBFOLDER);
define('EXO_TIMEZONE', 'America/Los_Angeles');
define('EXO_PATH', EXO_BASE_PATH . EXO_SUBFOLDER . '/exo');
define('EXO_APP_PATH', EXO_BASE_PATH . EXO_SUBFOLDER . '/app');
define('EXO_DEFAULT_THEME', 'default');
define('EXO_URL', EXO_BASE_URL . '/exo');
define('EXO_APP_URL', EXO_BASE_URL . '/app');

/**
 * Define the environment that this application is running in
 * Environments are defined in exo/config/environments.php
 */
define('EXO_DEBUG', TRUE); // allow detailed debug messages or stay generic

/**
 * The following config settings should not need touching
 * ... But you can if you really want to
 */
define('EXO_VERSION', '0.9.0');
define('EXO_REQUEST_KEY', '_EXO_REQUEST');
define('EXO_CONFIG_PATH', EXO_PATH . '/config');
define('EXO_CONFIG_SUFFIX', '.php');
define('EXO_APP_CONFIG_PATH', EXO_APP_PATH . '/config');
define('EXO_MODULES_PATH', EXO_PATH . '/modules');
define('EXO_APP_MODULES_PATH', EXO_APP_PATH . '/modules');
define('EXO_PLACEHOLDER_REGEXP', '\:[a-zA-Z][a-zA-Z0-9_]*');
define('EXO_NON_PLACEHOLDER_REGEXP', '[^a-zA-Z0-9_:]');
define('EXO_DEFAULT_ROUTE', 'default');
define('EXO_DEFAULT_CONTROLLER_METHOD', '_default');
define('EXO_REQUEST_SEGMENT_SEPARATOR', '/');
define('EXO_APP_HELPERS_PATH', EXO_APP_PATH . '/helpers');
define('EXO_HELPERS_PATH', EXO_PATH . '/helpers');
define('EXO_THEMES_PATH', EXO_APP_PATH . '/themes');
define('EXO_THEMES_URL', EXO_APP_URL . '/themes');
define('EXO_DEFAULT_TEMPLATE', 'default');
define('EXO_TEMPLATE_SUFFIX', '.php');
define('EXO_DEFAULT_SESSION_NAME', 'default');
define('EXO_APP_CACHE', EXO_APP_PATH . '/cache');
define('EXO_DEFAULT_DATABASE', 'default');
