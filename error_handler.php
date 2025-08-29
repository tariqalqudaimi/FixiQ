<?php

/**
 * =================================================================
 * == SIMPLE PHP ERROR HANDLER
 * =================================================================
 * This file allows you to switch between 'development' and 'production' modes.
 *
 * 'development': Shows all detailed PHP errors on the screen.
 * 'production': Hides all errors and shows a friendly 'unavailable.html' page on fatal errors.
 *
 */

// STEP 1: DEFINE YOUR ENVIRONMENT. THIS IS THE ONLY LINE YOU NEED TO CHANGE.
define('ENVIRONMENT', 'production'); // Change to 'development' when you are debugging.


if (ENVIRONMENT === 'development') {
    // --- DEVELOPMENT SETTINGS ---
    // Report all possible errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

} elseif (ENVIRONMENT === 'production') {
    // --- PRODUCTION SETTINGS ---
    // Don't display errors to the user
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);

    // Set up a function to run when the script shuts down
    register_shutdown_function('shutdownHandler');
}

/**
 * This function is called when the script execution finishes or is halted by a fatal error.
 * It checks if the shutdown was caused by a fatal error.
 */
function shutdownHandler()
{
    // Get the last error that occurred
    $error = error_get_last();

    // Check if it was a fatal error type
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        
        // Clear any previously sent content
        ob_clean();

        // Send a "503 Service Unavailable" HTTP header, which is good practice for SEO
        http_response_code(503);

        // Include our friendly error page
        // Use @ to suppress any errors that might occur while including the file
        @include('unavailable.html');

        // Stop the script
        exit();
    }
}