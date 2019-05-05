<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\MatchesHelper;

class HomeController extends BaseController
{
    /**
     * @var MatchesHelper
     */
    protected $matchesHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->matchesHelper = new MatchesHelper();
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getIndex(): string
    {
        $matches = $this->matchesHelper->getLatestMatches(3);

        return $this->twig->render('home.twig', [
            'nav' => [
                'active' => 'home'
            ],
            'latest' => $matches
        ]);
    }
}