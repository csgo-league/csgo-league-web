<?php

namespace Redline\League\Helpers;

class MatchesHelper extends BaseHelper
{
    const TABLE = 'sql_matches_scoretotal';

    /**
     * @param int|null $page
     * @return array
     */
    public function getMatches(?int $page = 1): array
    {
        try {
            $limit = env('LIMIT');
            $offset = ($page - 1) * $limit;

            $query = $this->db->query("SELECT * FROM ". self::TABLE ." ORDER BY match_id DESC LIMIT :offset, :limit", [
                ':offset' => $offset,
                ':limit' => (int)$limit
            ]);

            return $query->fetchAll();
        } catch (\Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }
}