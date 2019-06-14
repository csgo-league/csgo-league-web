<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
use B3none\League\Helpers\MatchesHelper;
use B3none\League\Helpers\PlayersHelper;
use Exception;

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

            $length = ceil($topPlayers / 2);
            $leftPlayers = array_slice($players, 0, $length);
            $rightPlayers = array_slice($players, $length);

            return $this->twig->render('home.twig', [
                'nav' => [
                    'active' => 'home',
                    'loggedIn' => $this->steam->loggedIn(),
                    'user' => $this->authorisedUser,
                    'discordInviteLink' => env('DISCORD')
                ],
                'baseTitle' => env('BASE_TITLE'),
                'description' => env('DESCRIPTION'),
                'website' => env('WEBSITE'),
                'title' => 'Home',
                'latestMatches' => $latestMatches,
                'leftPlayers' => $leftPlayers,
                'rightPlayers' => $rightPlayers
            ]);
        } catch (Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }
}
