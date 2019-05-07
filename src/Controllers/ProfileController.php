<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\MatchesHelper;
use Redline\League\Helpers\PlayersHelper;
use Redline\League\Helpers\ProfileHelper;

class ProfileController extends BaseController
{
    /**
     * @var ProfileHelper
     */
    protected $profileHelper;

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

        $this->profileHelper = new ProfileHelper();
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
            'matches' => $matches
        ]);
    }
}