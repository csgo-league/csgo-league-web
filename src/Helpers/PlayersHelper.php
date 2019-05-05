<?php

namespace Redline\League\Helpers;


class PlayersHelper extends BaseHelper
{
    const TABLE = 'sql_matches';

    /**
     * @param int $page
     * @return array
     */
    public function getPlayers(int $page): array
    {
        try {
            $limit = env('LIMIT');
            $offset = ($page - 1) * $limit;

            $query = $this->db->query("SELECT * FROM ". self::TABLE ." ORDER BY sql_matches_scoretotal.timestamp DESC LIMIT :offset, :limit", [
                ':offset' => $offset,
                ':limit' => (int)$limit
            ]);

            $response = $query->fetchAll();

            foreach ($response as $key => $match) {
                $response[$key] = $this->formatMatch($match);
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
     * @param int $players
     * @return array
     */
    public function getTopPlayers(int $players): array
    {
        try {
            $query = $this->db->query("SELECT * FROM ". self::TABLE ." ORDER BY sql_matches_scoretotal.timestamp DESC LIMIT :limit", [
                ':limit' => $players
            ]);

            $response = $query->fetchAll();

            foreach ($response as $key => $match) {
                $response[$key] = $this->formatMatch($match);
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
}