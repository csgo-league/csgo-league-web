<?php

use B3none\League\Helpers\ExceptionHelper;

if (file_exists(__DIR__ . '/../env.php')) {
    include __DIR__ . '/../env.php';

    if (!function_exists('env')) {
        function env(string $key, $default = null) {
            $value = getenv($key);
            if ($value === false) {
                return $default;
            }

            return $value;
        }
    }
}

if (env('API_KEYS') === '') {
    die('Please set the API_KEY value in the env.php');
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/pecee/simple-router/helpers.php');
require(__DIR__ . '/../app/Router.php');

// Set the exception handler globally
set_exception_handler(function(Throwable $error) {
    return ExceptionHelper::handle($error);
});

/**
 * Register routes
 */
(new Router())->initialiseRoutes();