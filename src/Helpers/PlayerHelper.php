<?php

namespace B3none\League\Helpers;

class PlayerHelper extends BaseHelper
{
    /**
     * Get player
     *
     * @param int|null $discordId
     * @return array
     */
    public function getPlayerByDiscordId(int $discordId): array
    {
        $query = $this->db->query('
            SELECT players.*, rankme.score as elo
            FROM rankme
            LEFT JOIN players ON players.steam = rankme.steam
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

        return $response;
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
