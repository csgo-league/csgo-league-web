<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
use B3none\League\Helpers\MatchesHelper;
use B3none\League\Helpers\PlayersHelper;
use Exception;

class ProfileController extends BaseController
{
    /**
     * @var PlayersHelper
     */
    protected $playersHelper;

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

        $this->playersHelper = new PlayersHelper();
        $this->matchesHelper = new MatchesHelper();
    }

    /**
     * @param string $steamId
     * @return string
     */
    public function getProfile(string $steamId): string
    {
        try {
            if ($steamId != (int)$steamId) {
                throw new Exception('Please use a steam64 id');
            }

            $player = $this->playersHelper->getPlayer($steamId);

            // If we don't have the player on our system redirect to the players page.
            if ($player === null) {
                response()->redirect('/players');
                die;
            }

            $matches = $this->matchesHelper->getPlayerMatches($steamId);

            return $this->twig->render('profile.twig', [
                'nav' => [
                    'active' => $this->authorisedUser['steamid'] == $steamId ? 'myprofile' : '',
                    'loggedIn' => $this->steam->loggedIn(),
                    'user' => $this->authorisedUser
                ],
                'player' => $player,
                'matches' => $matches,
                'baseTitle' => env('BASE_TITLE'),
                'description' => env('DESCRIPTION'),
                'title' => 'Profile',
            ]);
        } catch (Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }
}
