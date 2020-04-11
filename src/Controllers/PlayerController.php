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

            if (count($player) < 1 || !array_key_exists('steam', $player)) {
                return response()->httpCode(404)->json([
                    'error' => 'not_found'
                ]);
            } elseif (!$player['score']) {
                $player['score'] = 0;
            } else {
                $player['score'] = (int)$player['score'];
            }

            $player['inMatch'] = $this->playerHelper->isInMatch($player['steam']);

            return response()->json($player);
        } catch (Exception $exception) {
            return ExceptionHelper::handle($exception);
        }
    }

    /**
     * @param string $discordId
     * @return string
     */
    public function getBanPlayerByDiscordId(string $discordId): string
    {
        return response()->json($this->playerHelper->banPlayerByDiscordId($discordId));
    }

    /**
     * @param string $discordId
     * @return string
     */
    public function getUnbanPlayerByDiscordId(string $discordId): string
    {
        return response()->json($this->playerHelper->unbanPlayerByDiscordId($discordId));
    }

    /**
     * Get players.
     *
     * @param string $discordId
     * @return string
     */
    public function getPlayerMatchByDiscordId(string $discordId): string
    {
        try {
            $match = $this->playerHelper->getPlayerMatchByDiscordId($discordId);

            if (count($match) < 1 || !array_key_exists('team1_score', $match)) {
                return response()->httpCode(404)->json([
                    'error' => 'not_found'
                ]);
            }

            return response()->json($match);
        } catch (Exception $exception) {
            return ExceptionHelper::handle($exception);
        }
    }

    /**
     * Get players by discord Ids.
     *
     * @return string
     */
    public function getPlayersByDiscordIds(): string
    {
        try {
            $data = input()->all();

            if (!array_key_exists('discordIds', $data)) {
                return response()->httpCode(422)->json([
                    'error' => 'invalid_discord_ids',
                ]);
            } elseif (!is_array($data['discordIds'])) {
                return response()->httpCode(422)->json([
                    'error' => 'invalid_discord_ids',
                ]);
            } elseif (count($data['discordIds']) < 1) {
                return response()->httpCode(422)->json([
                    'error' => 'invalid_discord_ids',
                ]);
            }

            $players = $this->playerHelper->getPlayersByDiscordIds($data['discordIds']);

            if (array_key_exists('error', $players)) {
                return response()->json($players);
            }

            $formattedPlayers = [];
            foreach ($players as $key => $player) {
                if (count($player) < 1 || !array_key_exists('steam', $player)) {
                    continue;
                } elseif (!$player['score']) {
                    $player['score'] = 0;
                } else {
                    $player['score'] = (int)$player['score'];
                }

                $player['inMatch'] = $this->playerHelper->isInMatch($player['steam']);

                $formattedPlayers[] = $player;
            }

            return response()->json($formattedPlayers);
        } catch (Exception $exception) {
            return ExceptionHelper::handle($exception);
        }
    }
}
