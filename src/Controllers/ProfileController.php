<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\MatchesHelper;
use B3none\League\Helpers\PlayersHelper;

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
            $player = $this->playersHelper->getPlayer($steamId);

            if ($player === null) {
                response()->redirect('/players');
                die;
            }

            $matches = $this->matchesHelper->getPlayerMatches($steamId);

            return $this->twig->render('profile.twig', [
                'player' => $player,
                'matches' => $matches,
                'baseTitle' => env('BASE_TITLE'),
            ]);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
            'status' => 500
            ]);

            die;
        }
    }
}
