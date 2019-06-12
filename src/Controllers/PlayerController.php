<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
use B3none\League\Helpers\PlayerHelper;
use Exception;

class PlayerController extends BaseController
{
    /**
     * @var PlayerHelper
     */
    protected $playerHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->playerHelper = new PlayerHelper();
    }

    /**
     * Get players.
     *
     * @param string $discordId
     * @return string
     */
    public function getPlayerByDiscordId(string $discordId): string
    {
        try {
            $player = $this->playerHelper->getPlayerByDiscordId($discordId);

            if (count($player) < 1) {
                $player = [
                    'error' => 'not_found'
                ];
            }

            return json_encode(
                $player
            );
        } catch (Exception $exception) {
            ExceptionHelper::handle($exception);
        }
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
                'searchedValue' => $search,
                'title' => 'Players',
                'baseTitle' => env('BASE_TITLE'),
                'description' => env('DESCRIPTION'),
            ]);
        } catch (Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }
}
