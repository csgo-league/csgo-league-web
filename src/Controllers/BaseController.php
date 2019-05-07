<?php

namespace Redline\League\Controllers;

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

        $this->twig = new \Twig\Environment($loader, [
//            'cache' => __DIR__ . '/../../app/cache/twig',
        ]);
    }
}