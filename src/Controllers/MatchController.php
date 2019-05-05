<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\MatchHelper;

class MatchController extends BaseController
{
    /**
     * @var MatchHelper
     */
    protected $matchHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->matchHelper = new MatchHelper();
    }

    /**
     * @param string $matchId
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getMatch(string $matchId): string
    {
        $match = $this->matchHelper->getMatchPlayers($matchId);

        return $this->twig->render('match.twig', array_merge($match, [
            'nav' => [
                'active' => 'matches'
            ]
        ]));
    }
}