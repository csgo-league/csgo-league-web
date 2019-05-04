<?php

namespace Redline\League\Helpers;

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
            $pdo = new \PDO("mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_NAME'), env('DB_USERNAME'), env('DB_PASSWORD'));
            $this->db = new PDOHandler($pdo);
        } catch (\Exception $e) {
            die('There was an error connecting to the database.');
        }
    }
}