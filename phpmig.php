<?php

use \Phpmig\Adapter;

if (file_exists(__DIR__ . '/env.php')) {
    include __DIR__ . '/env.php';

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

$container = new ArrayObject();

// replace this with a better Phpmig\Adapter\AdapterInterface
$container['phpmig.adapter'] = new Adapter\File\Flat(__DIR__ . DIRECTORY_SEPARATOR . 'src/Migrations/.migrations.log');

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'src/Migrations';

$container['db'] = \B3none\League\Helpers\BaseHelper::getDatabaseHandler();

return $container;