<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\PlayersHelper;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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

        if ($page < 1) {
            response()->redirect('/players/');
        }

        $totalPlayers = $this->playersHelper->getPlayersCount();
        $totalPages = ceil($totalPlayers / 12);

        if ($page > $totalPages) {
            response()->redirect('/players/' . $totalPages);
        }

        $players = $this->playersHelper->getPlayers($page);

        return $this->twig->render('players.twig', [
            'nav' => [
                'active' => 'players'
            ],
            'players' => $players,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => ceil($totalPlayers / 12),
                'link' => 'players'
            ]
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function postIndex(): string
    {
        $search = input()->post('search')->getValue();

        if (!$search) {
            response()->redirect('/players');
        }

        $players = $this->playersHelper->searchPlayers($search);

        return $this->twig->render('players.twig', [
            'nav' => [
                'active' => 'players'
            ],
            'players' => $players,
            'searchedValue' => $search
        ]);
    }
}