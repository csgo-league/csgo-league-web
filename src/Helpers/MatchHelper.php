<?php

namespace Redline\League\Helpers;


class MatchHelper extends BaseHelper
{
    const TABLE = 'sql_matches';

    /**
     * @param string $matchId
     * @return array
     */
    public function getMatchPlayers(string $matchId): array
    {
        try {
            $query = $this->db->query("SELECT sql_matches_scoretotal.*, sql_matches.*
                FROM sql_matches_scoretotal INNER JOIN sql_matches
                ON sql_matches_scoretotal.match_id = sql_matches.match_id
                WHERE sql_matches_scoretotal.match_id = :matchId ORDER BY sql_matches.score DESC", [
                ':matchId' => $matchId,
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

    /**
     * @param array $players
     * @return array
     */
    protected function formatMatchPlayers(array $players): array
    {
        $formattedPlayers = [
            'ct' => [
                'players' => [],
                'name' => '',
                'score' => ''
            ],
            't' => [
                'players' => [],
                'name' => '',
                'score' => ''
            ],
        ];

        foreach ($players as $player) {
            if ($player['team'] == 2) {
                $formattedPlayers['ct']['players'][] = $this->formatMatchPlayer($player);
                $formattedPlayers['ct']['name'] = $player['teamname_2'] ?: 'Counter-Terrorists';
                $formattedPlayers['ct']['score'] = $player['team_2'];
            } else if ($player['team'] == 3) {
                $formattedPlayers['t']['players'][] = $this->formatMatchPlayer($player);
                $formattedPlayers['t']['name'] = $player['teamname_3'] ?: 'Terrorists';
                $formattedPlayers['t']['score'] = $player['team_3'];
            }
        }

        return $formattedPlayers;
    }

    /**
     * @param array $player
     * @return array
     */
    protected function formatMatchPlayer(array $player): array
    {
        if ($player['kills'] > 0 && $player['deaths'] > 0) {
            $player['kdr'] = round(($player['kills'] / $player['deaths']), 2);
        } else {
            $player['kdr'] = 0;
        }

        $player['name'] = htmlspecialchars(substr($player['name'],0,32));

        return $player;
    }
}