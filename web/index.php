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

require(__DIR__ . "/../vendor/autoload.php");
require(__DIR__ . "/../vendor/pecee/simple-router/helpers.php");
require(__DIR__ . "/../app/Router.php");

$a = new \Redline\League\Helpers\MatchesHelper();
echo json_encode($a->getMatches());
die;

/**
 * Register routes
 */
(new Router())->initialiseRoutes();