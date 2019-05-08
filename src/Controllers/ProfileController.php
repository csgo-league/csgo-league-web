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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getProfile(string $steamId): string
    {
        $player = $this->playersHelper->getPlayer($steamId);
        $matches = $this->matchesHelper->getPlayerMatches($steamId);

        return $this->twig->render('profile.twig', [
            'player' => $player,
            'matches' => $matches,
            'baseTitle' => env('BASE_TITLE'),
        ]);
    }
}