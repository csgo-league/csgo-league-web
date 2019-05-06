<?php

namespace Redline\League\Helpers;

use B3none\SteamIDConverter\Client as Converter;
use Redline\League\Models\PlayerModel;

class PlayersHelper extends BaseHelper
{
    const TABLE = 'sql_matches';

    /**
     * @var Converter
     */
    protected $converter;

    public function __construct()
    {
        parent::__construct();

        $this->converter = Converter::create();

        // This is a filthy hack to make sure that all of our players have a steam64 id
        $this->updatePlayers();
    }

    /**
     * Get the total number of players
     *
     * @return int
     */
    public function getPlayersCount(): int
    {
        return $this->db->count('rankme');
    }

    /**
     * Get players
     *
     * @param int $page
     * @return array
     */
    public function getPlayers(int $page): array
    {
        try {
            $limit = 12;
            $offset = ($page - 1) * $limit;

            $query = $this->db->query("SELECT * FROM rankme ORDER BY rankme.score DESC LIMIT :offset, :limit", [
                ':offset' => $offset,
                ':limit' => (int)$limit
            ]);

            $response = $query->fetchAll();

            foreach ($response as $key => $player) {
                $response[$key] = $this->formatPlayer($player);
            }

            return $response;
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }

    /**
     * Get top players
     *
     * @param int $players
     * @return array
     */
    public function getTopPlayers(int $players): array
    {
        try {
            $query = $this->db->query("SELECT * FROM rankme ORDER BY rankme.score DESC LIMIT :limit", [
                ':limit' => $players
            ]);

            $response = $query->fetchAll();

            foreach ($response as $key => $player) {
                $response[$key] = $this->formatPlayer($player);
            }

            return $response;
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }

    /**
     * Search players
     *
     * @param string $search
     * @return array
     */
    public function searchPlayers(string $search): array
    {
        try {
            $query = $this->db->query("SELECT steam, steamid64, score, kills, deaths, assists FROM rankme WHERE name LIKE :like_search OR steam = :search OR steamid64 = :search ORDER BY score DESC", [
                ':search' => $search,
                ':like_search' => '%'.$search.'%',
            ]);

            $response = $query->fetchAll();

            foreach ($response as $key => $player) {
                $response[$key] = $this->formatPlayer($player);
            }

            return $response;
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }

    /**
     * @param array $player
     * @return array
     */
    public function formatPlayer(array $player): array
    {
        $playerModel = new PlayerModel($player);

        $player['kdr'] = $playerModel->getKDR();
        $player['adr'] = $playerModel->getADR();
        $player['accuracy'] = $playerModel->getAccuracy();

        return $player;
    }

    /**
     * Update players
     */
    public function updatePlayers(): void
    {
        try {
            $query = $this->db->query("SELECT * FROM rankme WHERE steamid64 IS NULL AND steam IS NOT NULL");

            $response = $query->fetchAll();

            foreach ($response as $player) {
                $steam = $this->converter->createFromSteamID($player['steam']);

                $this->db->update('rankme', [
                    'steamid64' => $steam->getSteamID64()
                ], [
                    'steam' => $player['steam']
                ]);
            }
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }
}