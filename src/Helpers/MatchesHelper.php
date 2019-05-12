<?php

namespace B3none\League\Helpers;

class MatchesHelper extends BaseHelper
{
    /**
     * Get the total number of matches
     *
     * @return int
     */
    public function getMatchesCount(): int
    {
        return $this->db->count('sql_matches_scoretotal');
    }

    /**
     * Get the matches
     *
     * @param int $page
     * @return array
     */
    public function getMatches(int $page = 1): array
    {
        $limit = env('MATCHES_PAGE_LIMIT');
        $offset = ($page - 1) * $limit;

        $query = $this->db->query('
            SELECT * 
            FROM sql_matches_scoretotal
            ORDER BY sql_matches_scoretotal.timestamp DESC LIMIT :offset, :limit
        ', [
            ':offset' => $offset,
            ':limit' => (int)$limit
        ]);

        $response = $query->fetchAll();

        foreach ($response as $key => $match) {
            $response[$key] = $this->formatMatch($match);
        }

        return $response;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getLatestMatches(int $limit = 3)
    {
        $query = $this->db->query('
            SELECT * 
            FROM sql_matches_scoretotal 
            ORDER BY sql_matches_scoretotal.timestamp DESC LIMIT :limit
        ', [
            ':limit' => (int)$limit
        ]);

        $response = $query->fetchAll();

        foreach ($response as $key => $match) {
            $response[$key] = $this->formatMatch($match);
        }

        return $response;
    }

    /**
     * @param string $search
     * @return array
     */
    public function searchMatches(string $search): array
    {
        $query = $this->db->query('
            SELECT DISTINCT 
            sql_matches_scoretotal.match_id,
            sql_matches_scoretotal.map,
            sql_matches_scoretotal.team_2,
            sql_matches_scoretotal.team_3,
            sql_matches_scoretotal.timestamp
            FROM sql_matches_scoretotal 
            INNER JOIN sql_matches ON sql_matches_scoretotal.match_id = sql_matches.match_id
            WHERE sql_matches.name LIKE :like_search 
            OR sql_matches.steam64 = :search 
            OR sql_matches_scoretotal.match_id = :search
            ORDER BY sql_matches_scoretotal.timestamp DESC
        ', [
            ':search' => $search,
            ':like_search' => '%'.$search.'%',
        ]);

        $response = $query->fetchAll();

        foreach ($response as $key => $match) {
            $response[$key] = $this->formatMatch($match);
        }

        return $response;
    }

    /**
     * Format a match ready for the frontend
     *
     * @param array $match
     * @return array
     */
    protected function formatMatch(array $match): array
    {
        $half = ($match['team_2'] + $match['team_3']) / 2;
        $half = ceil($half);

        if ($match['team_2'] > $half) {
            $match['icon'] = 'ct_icon.png';
        } elseif ($match['team_2'] == $half && $match['team_3'] == $half) {
            $match['icon'] = 'tie_icon.png';
        } else {
            $match['icon'] = 't_icon.png';
        }

        $match['map_image'] = $this->getMatchMapImage($match['map']);

        return $match;
    }

    /**
     * Get match map image
     *
     * @param string $map
     * @return string
     */
    protected function getMatchMapImage(string $map): string
    {
        $rawMaps = explode(',', env('MAPS'));
        $maps = [];
        for ($i = 0; $i < count($rawMaps); $i += 2) {
            $maps[$rawMaps[$i]] = $rawMaps[$i + 1];
        }

        if (array_key_exists($map, $maps)) {
            return $maps[$map];
        } else {
            return $maps['de_austria'];
        }
    }

    /**
     * Get a players past matches
     *
     * @param string $steamId
     * @param int $matches
     * @return array
     */
    public function getPlayerMatches(string $steamId, int $matches = 3): array
    {
        $query = $this->db->query('
            SELECT 
            sql_matches_scoretotal.match_id, 
            sql_matches_scoretotal.timestamp, 
            sql_matches_scoretotal.map, 
            sql_matches.kills,
            sql_matches.deaths
            
            FROM sql_matches 
            JOIN sql_matches_scoretotal 
            ON sql_matches_scoretotal.match_id = sql_matches.match_id
            WHERE sql_matches.steam64 = :steam 
            ORDER BY sql_matches_scoretotal.timestamp DESC LIMIT :limit
        ', [
            ':steam' => $steamId,
            ':limit' => $matches,
        ]);

        $response = $query->fetchAll();

        foreach ($response as $key => $match) {
            $response[$key]['map_image'] = $this->getMatchMapImage($match['map']);
        }

        return $response;
    }
}
