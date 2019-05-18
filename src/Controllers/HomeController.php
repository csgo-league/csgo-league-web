<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
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
     */
    public function getIndex(): string
    {
        try {
            $latestMatches = $this->matchesHelper->getLatestMatches(3);

            $topPlayers = 6;
            $players = $this->playersHelper->getTopPlayers($topPlayers);

            $leftPlayers = array_slice($players, 0, $topPlayers / 2);
            $rightPlayers = array_slice($players, $topPlayers / 2);

            return $this->twig->render('home.twig', [
                'nav' => [
                    'active' => 'home',
                    'loggedIn' => $this->steam->loggedIn(),
                    'user' => $this->authorisedUser
                ],
                'baseTitle' => env('BASE_TITLE'),
                'description' => env('DESCRIPTION'),
                'website' => env('WEBSITE'),
                'title' => 'Home',
                'latestMatches' => $latestMatches,
                'leftPlayers' => $leftPlayers,
                'rightPlayers' => $rightPlayers
            ]);
        } catch (\Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }
}
