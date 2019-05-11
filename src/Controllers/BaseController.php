<?php

namespace B3none\League\Controllers;

class BaseController
{
    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../Views');

        if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1') {
            $this->twig = new \Twig\Environment($loader);
        } else {
            $this->twig = new \Twig\Environment($loader, [
                'cache' => __DIR__ . '/../../app/cache/twig',
            ]);
        }
    }
}
