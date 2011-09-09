<?php
/**
 * ExoSkeleton Autoloader
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */
if (!defined('EXO')) { exit('This file cannot be accessed directly.'); }

/**
 * Autoload the requested class file
 * @param string $class the name of the class being requested
 * @return void
 */
function __autoload($class)
{
    $filename = str_replace('_', '/', $class) . '.php';
    $app_filename = EXO_APP_MODULES_PATH . '/' . $filename;

    if (file_exists($app_filename))
    {
        require_once($app_filename);
        return;
    }

    $exo_filename = EXO_MODULES_PATH . '/' . $filename;
    if (file_exists($exo_filename))
    {
        require_once($exo_filename);
        return;
    }

    throw new Exception('The requested class "' . $class . '" could not be found');
    return;
}
