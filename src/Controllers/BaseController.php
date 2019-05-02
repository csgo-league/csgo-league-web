<?php

namespace Redline\League\Controllers;


class BaseController
{
    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var \PDO
     */
    protected $PDO;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../Views');

        $this->twig = new \Twig\Environment($loader, [
            'cache' => __DIR__ . '/../../app/cache',
        ]);

        try {
            $this->PDO = new \PDO("mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_NAME'), env('DB_USERNAME'), env('DB_PASSWORD'));
        } catch (\Exception $e) {
            die('There was an errors connecting to the database.');
        }
    }
}