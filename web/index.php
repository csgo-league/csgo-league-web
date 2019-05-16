<?php

if (file_exists(__DIR__ . '/../env.php')) {
    include __DIR__ . '/../env.php';

    if (!function_exists('env')) {
        function env(string $key, $default = null)
        {
            $value = getenv($key);
            if ($value === false) {
                return $default;
            }

            return $value;
        }
    }
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/pecee/simple-router/helpers.php');
require(__DIR__ . '/../app/Router.php');

// Set the exception handler globally
set_exception_handler(function(Throwable $error) {
    return \B3none\League\Helpers\ExceptionHelper::handle($error);
});

/**
 * Register routes
 */
(new Router())->initialiseRoutes();