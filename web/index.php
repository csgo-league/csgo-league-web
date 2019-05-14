<?php

if (file_exists(__DIR__ . '/../env.php')) {
    include __DIR__ . '/../env.php';
}

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

// Set the exception handler globally
set_exception_handler(function (Exception $exception) {
    header('HTTP/1.1 500 Internal Server Error');

    $response = [
        'status' => 500
    ];

    $remote = $_SERVER['REMOTE_ADDR'];
    if ($remote !== '127.0.0.1' && $remote !== '::1') {
        $response = array_merge($response, [
            'error' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ]);
    }

    echo json_encode($response);

    die;
});

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/pecee/simple-router/helpers.php');
require(__DIR__ . '/../app/Router.php');

/**
 * Register routes
 */
(new Router())->initialiseRoutes();