<?php
/**
 * ExoSkeleton Initializer
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

require_once('exo/init.php');

try
{
    // instantiate the loader
    $load = new Exo_Loader();

    // load environment-specific settings
    $load->environment();

    // load appropriate helpers libraries
    $load->helpers();

    // load the appropriate route
    $load->route();

} catch (Exception $exception) {

    header("Status: 500 Internal Server Error");
    ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Framework Exception</title>
        <meta charset="UTF-8">
        <style type="text/css">
        .exception { font-family: monospace; margin: 30px; white-space: normal; }
        .exception pre { margin-bottom: 35px; display: block; min-width: 700px; border-radius: 10px; box-shadow: 5px 5px 10px #ccc; white-space: pre-wrap; font-size: 12px; border: 1px solid #888; color: #444; background-color: #eee; padding: 10px 15px 10px 15px; }
        .exception .framework { cursor: default; text-align: right; font-size: 0.7em; display: block; font-family: Verdana, sans-serif; color: #888; }
        .exception .framework a { margin-left: 10px; text-decoration: none; padding: 3px 7px 3px 7px; border-radius: 5px; display: inline-block; background-color: #aaa; color: #fff; }
        .exception .framework a:hover { background-color: #888; }
        </style>
    </head>
    <body>
        <div class="exception">
            <h1>Framework Exception</h1>
            <?php if (defined('EXO_DEBUG') && EXO_DEBUG): ?>
            <h2>The following <?= get_class($exception) ?> was uncaught:</h2>
            <pre class="stack"><?= $exception ?></pre>
            <?php else: ?>    
            <h2>An exception was not properly handled by the application</h2>
            <?php endif; ?>
            <em class="framework" style="color: #888;"><?= date('Y-m-d H:i:s'); ?> <a href="http://www.exoduslabs.ca/framework" target="_blank">ExoSkeleton Framework</a></em>
        </div>
    </body>
</html>
    <?php
    exit();
}
