<?php

namespace Redline\League\Controllers;


class BaseController
{
    protected $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../src/Views');
    }
}