<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\MatchesHelper;
use B3none\League\Helpers\PlayersHelper;

class HomeController extends BaseController
{
    /**
     * @var MatchesHelper
     */
    protected $matchesHelper;

    /**
     * @var PlayersHelper
     */
    protected $playersHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->matchesHelper = new MatchesHelper();
        $this->playersHelper = new PlayersHelper();
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

        $topPlayers = 6;
        $players = $this->playersHelper->getTopPlayers($topPlayers);

        $leftPlayers = array_slice($players, 0, $topPlayers / 2);
        $rightPlayers = array_slice($players, $topPlayers / 2);

        return $this->twig->render('home.twig', [
            'nav' => [
                'active' => 'home'
            ],
            'baseTitle' => env('BASE_TITLE'),
            'title' => 'Home',
            'latest' => $matches,
            'leftPlayers' => $leftPlayers,
            'rightPlayers' => $rightPlayers
        ]);
    }
}