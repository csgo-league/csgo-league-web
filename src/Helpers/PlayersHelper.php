<?php

namespace B3none\League\Helpers;

use B3none\SteamIDConverter\Client as Converter;
use B3none\League\Models\PlayerModel;

class PlayersHelper extends BaseHelper
{
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
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $query = $this->db->query('
            SELECT *
            FROM rankme
            LEFT JOIN players ON players.steam = rankme.steam
            ORDER BY rankme.score DESC LIMIT :offset, :limit
        ', [
            ':offset' => $offset,
            ':limit' => (int)$limit
        ]);

        $response = $query->fetchAll();

        foreach ($response as $key => $player) {
            $response[$key] = $this->formatPlayer($player);
        }

        return $response;
    }

    /**
     * Get top players
     *
     * @param int $players
     * @return array
     */
    public function getTopPlayers(int $players): array
    {
        $query = $this->db->query('
            SELECT *
            FROM rankme
            LEFT JOIN players ON players.steam = rankme.steam
            ORDER BY rankme.score DESC LIMIT :limit
        ', [
            ':limit' => $players
        ]);

        $response = $query->fetchAll();

        foreach ($response as $key => $player) {
            $response[$key] = $this->formatPlayer($player);
        }

        return $response;
    }

    /**
     * Search players
     *
     * @param string $search
     * @return array
     */
    public function searchPlayers(string $search): array
    {
        $query = $this->db->query('
            SELECT * FROM rankme 
            LEFT JOIN players ON players.steam = rankme.steam
            WHERE name LIKE :like_search OR steam = :search OR steam = :search 
            ORDER BY score DESC
        ', [
            ':search' => $search,
            ':like_search' => '%'.$search.'%',
        ]);

        $response = $query->fetchAll();

        foreach ($response as $key => $player) {
            $response[$key] = $this->formatPlayer($player);
        }

        return $response;
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
        $query = $this->db->query('
            SELECT rankme.steam 
            FROM rankme 
            WHERE rankme.steam IS NOT NULL AND rankme.steam NOT IN (SELECT steam FROM players)
        ');

        $response = $query->fetchAll();

        foreach ($response as $player) {
            $this->db->insert('players', [
                'steam' => $player['steam']
            ]);
        }
    }

    /**
     * Get player
     *
     * @param string $steamId
     * @return array|null
     */
    public function getPlayer(string $steamId): ?array
    {
        $query = $this->db->query('
            SELECT * 
            FROM rankme 
            LEFT JOIN players ON players.steam = rankme.steam 
            WHERE players.steam = :steam
        ', [
            ':steam' => $steamId,
        ]);

        $player = $query->fetch();

        return $player ? $this->formatPlayer($player) : null;
    }

    /**
     * Check whether a steamId is linked
     *
     * @param string $steamId
     * @return bool
     */
    public function addPlayer(string $steamId): bool
    {
        $query = $this->db->insert('players', [
            'steam' => $steamId
        ]);

        return !! $query;
    }

    /**
     * Check whether a steamId is linked
     *
     * @param string $steamId
     * @return bool
     */
    public function isLinked(string $steamId): bool
    {
        $query = $this->db->query('
            SELECT * 
            FROM players
            WHERE steam = :steam
        ', [
            ':steam' => $steamId,
        ]);

        return $query->rowCount() !== 0;
    }
}
