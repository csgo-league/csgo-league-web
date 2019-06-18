<?php

namespace B3none\League\Helpers;

use B3none\League\Models\PlayerModel;

class PlayersHelper extends BaseHelper
{
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
            /*LEFT JOIN players ON players.steam = rankme.steam*/
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
}
