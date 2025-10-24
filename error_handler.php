<?php
define('ENVIRONMENT', 'production');

if (ENVIRONMENT === 'development') {

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} elseif (ENVIRONMENT === 'production') {

    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    register_shutdown_function('shutdownHandler');
}

function shutdownHandler()
{
    $error = error_get_last();

    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_clean();

        http_response_code(503);

        @include('unavailable.html');

        exit();
    }
}
