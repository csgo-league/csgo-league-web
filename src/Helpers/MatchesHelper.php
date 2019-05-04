<?php

namespace Redline\League\Helpers;

class MatchesHelper extends BaseHelper
{
    const TABLE = 'sql_matches_scoretotal';

    /**
     * Get the total number of matches
     *
     * @return int
     */
    public function getMatchesCount(): int
    {
        return $this->db->count(self::TABLE);
    }

    /**
     * Get the matches
     *
     * @param int $page
     * @return array
     */
    public function getMatches(int $page = 1): array
    {
        try {
            $limit = env('LIMIT');
            $offset = ($page - 1) * $limit;

            $query = $this->db->query("SELECT * FROM ". self::TABLE ." ORDER BY match_id DESC LIMIT :offset, :limit", [
                ':offset' => $offset,
                ':limit' => (int)$limit
            ]);

            $response = $query->fetchAll();

            foreach ($response as $key => $match) {
                $response[$key] = $this->formatMatch($match);
            }

            return $response;
        } catch (\Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }

    /**
     * Format a match ready for the frontend
     *
     * @param array $match
     * @return array
     */
    protected function formatMatch(array $match): array
    {
        $half = $match['team_2'] + $match['team_3'];

        if ($match['team_2'] > $half) {
            $match['icon'] = 'ct_icon.png';
        } elseif ($match['team_2'] == $half && $match['team_3'] == $half) {
            $match['icon'] = 'tie_icon.png';
        } else {
            $match['icon'] = 't_icon.png';
        }

        $rawMaps = explode(',', env('MAPS'));
        $maps = [];
        for ($i = 0; $i < count($rawMaps); $i += 2) {
            $maps[$rawMaps[$i]] = $rawMaps[$i + 1];
        }

        if (array_key_exists($match['map'], $maps)) {
            $match['map_image'] = $maps[$match['map']];
        } else {
            $match['map_image'] = 'austria.jpg';
        }

        return $match;
    }
}