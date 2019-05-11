<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\PlayersHelper;

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
     * @param string|null $page
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getPlayers(?string $page = null): string
    {
        $limit = env('PLAYERS_PAGE_LIMIT');
        $page = $page ?? 1;

        if ($page < 1) {
            response()->redirect('/players/');
        }

        $totalPlayers = $this->playersHelper->getPlayersCount();
        $totalPages = ceil($totalPlayers / $limit);

        if ($page > $totalPages) {
            response()->redirect('/players/' . $totalPages);
        }

        $players = $this->playersHelper->getPlayers($page);

        return $this->twig->render('players.twig', [
            'nav' => [
                'active' => 'players',
                'loggedIn' => $this->steam->loggedIn(),
                'user' => $this->authorisedUser
            ],
            'baseTitle' => env('BASE_TITLE'),
            'title' => 'Players',
            'players' => $players,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => ceil($totalPlayers / $limit),
                'link' => 'players'
            ]
        ]);
    }

    /**
     * @return string
     */
    public function postIndex(): string
    {
        try {
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
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
            'status' => 500
            ]);

            die;
        }
    }
}
