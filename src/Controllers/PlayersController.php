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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getPlayers(?string $page = null): void
    {
        $page = $page ?? 1;

        $players = $this->playersHelper->getPlayers($page);

        $this->twig->render('players.twig', [

        ]);
    }
}