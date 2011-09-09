<?php
/**
 * ExoSkeleton Error Handler
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

function __exo_error_handler($errno, $errstr)
{
    throw new Exo_Exception($errstr);
}
function __exo_exception_handler($exception)
{
    throw new Exo_Exception($exception->getMessage());
}
//set_error_handler('__exo_error_handler');
set_exception_handler('__exo_exception_handler');
