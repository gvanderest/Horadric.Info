<?php
/**
 * ExoSkeleton Configuration Files Loader
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

if (!defined('EXO')) { exit('This file can not be accessed directly'); }

// load configuration files
foreach (array(EXO_CONFIG_PATH, EXO_APP_CONFIG_PATH) as $dir)
{
    if (is_dir($dir))
    {
        $fp = opendir($dir);
        while ($filename = readdir($fp))
        {
            $path = $dir . '/' . $filename;
            if (file_exists($path) && !is_dir($path) && substr($path, -1 * strlen(EXO_CONFIG_SUFFIX)) == EXO_CONFIG_SUFFIX)
            {
                require_once($path);
            }
        }
        closedir($fp);
    }
}
