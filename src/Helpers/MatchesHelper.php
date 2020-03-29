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
        return $this->db->count('matches');
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
            SELECT matches_maps.* 
            FROM matches
            LEFT JOIN matches_maps ON matches_maps.matchid = matches.matchid
            ORDER BY matches.end_time 
            DESC LIMIT :offset, :limit
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
            SELECT matches_maps.* 
            FROM matches
            LEFT JOIN matches_maps ON matches_maps.matchid = matches.matchid
            ORDER BY matches.end_time 
            DESC LIMIT :limit
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
            matches_maps.*
            FROM matches
            LEFT JOIN matches_maps ON matches_maps.matchid = matches.matchid
            LEFT JOIN matches_players ON matches_players.matchid = matches.matchid
            WHERE matches_players.name LIKE :like_search 
            OR matches_players.steam = :search 
            OR matches.matchid = :search
            ORDER BY matches_maps.end_time DESC
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
        $half = ($match['team1_score'] + $match['team2_score']) / 2;
        $half = ceil($half);

        if ($match['team1_score'] > $half) {
            $match['icon'] = 'ct_icon.png';
        } elseif ($match['team1_score'] == $half && $match['team2_score'] == $half) {
            $match['icon'] = 'tie_icon.png';
        } else {
            $match['icon'] = 't_icon.png';
        }

        $match['map_image'] = $this->getMatchMapImage($match['mapname']);

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
            SELECT DISTINCT
            matches_maps.*
            FROM matches
            LEFT JOIN matches_maps ON matches_maps.matchid = matches.matchid
            LEFT JOIN matches_players ON matches_players.matchid = matches.matchid
            WHERE matches_players.steam = :steam
            ORDER BY matches_maps.end_time DESC LIMIT :limit
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
