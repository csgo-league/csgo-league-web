<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\PlayersHelper;

class PlayersController extends BaseController
{
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

        $this->playersHelper = new PlayersHelper();
    }

    /**
     * Get players.
     *
     * @param null|string $page
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getPlayers(?string $page = null): string
    {
        $page = $page ?? 1;

        $players = $this->playersHelper->getPlayers($page);

        return $this->twig->render('players.twig', [
            'players' => $players
        ]);
    }
}