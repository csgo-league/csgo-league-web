<?php

namespace B3none\League\Helpers;

class PlayerHelper extends BaseHelper
{
    /**
     * Get player
     *
     * @param int $discordId
     * @return array
     */
    public function getPlayerByDiscordId(int $discordId): array
    {
        $query = $this->db->query('
            SELECT *, rankme.steam
            FROM players
            LEFT JOIN rankme ON rankme.steam = players.steam
            WHERE players.discord = :discord
        ', [
            ':discord' => $discordId,
        ]);

        $response = $query->fetch();

        foreach ($response as $key => $value) {
            if (is_numeric($key)) {
                unset($response[$key]);
            }
        }

        return $response ?: [
            'error' => 'not_found'
        ];
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

        return !!$query;
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

    /**
     * Check whether a steamId is in an ongoing game
     *
     * @param string $steamId
     * @return bool
     */
    public function isInMatch(string $steamId): bool
    {
        $response = $this->db->get('matches', [
            '[<]matches_players' => 'matchid'
        ], [
            'matches.matchid',
            'matches_players.steam'
        ], [
            'matches_players.steam' => $steamId,
            'matches.end_time' => null,
        ]);

        return !!$response;
    }
}
