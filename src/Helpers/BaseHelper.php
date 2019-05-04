<?php

namespace Redline\League\Helpers;

use Medoo\Medoo;
use Redline\League\Handlers\PDOHandler;

class BaseHelper
{
    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var PDOHandler
     */
    protected $db;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../Views');

        $this->twig = new \Twig\Environment($loader, [
            'cache' => __DIR__ . '/../../app/cache',
        ]);

        try {
            $this->db = new Medoo([
                'database_type' => 'mysql',
                'database_name' => env('DB_NAME'),
                'server' => env('DB_HOST'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD')
            ]);
        } catch (\Exception $e) {
            die('There was an error connecting to the database.');
        }
    }
}