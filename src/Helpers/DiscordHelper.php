<?php

namespace B3none\League\Helpers;

class DiscordHelper extends BaseHelper
{
    /**
     * @var CodeHelper
     */
    protected $code;

    /**
     * DiscordHelper constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->code = new CodeHelper();
    }

    /**
     * Generate a discord link code.
     *
     * @param string $discordId
     * @return array
     */
    public function generateDiscordLinkCode(string $discordId): array
    {
        if ($this->isAlreadyLinked($discordId)) {
            return [
                'code' => null,
                'error' => 'It looks like you\'re already linked on our system.'
            ];
        }

        $query = $this->db->query('SELECT * FROM player_link_codes WHERE discord = :discordId', [
            'discordId' => $discordId
        ]);

        if ($query->rowCount() > 0) {
            return $query->fetch();
        }

        $attempts = 0;
        $length = 5;
        do {
            $code = $this->code->generate($length);

            if (++$attempts == 3) {
                $length += 1;
                $attempts = 0;
            }
        } while ($this->doesCodeExist($code));

        $insert = [
            'discord' => $discordId,
            'expires' => time() + (60 * 15), // 15 minutes
            'code' => $code
        ];
        $success = !!$this->db->insert('player_link_codes', $insert);

        return $success ? $insert : [
            'code' => null,
            'error' => 'Something has gone horribly wrong.'
        ];
    }

    /**
     * @param string $discordId
     * @return bool
     */
    protected function isAlreadyLinked(string $discordId): bool
    {
        $query = $this->db->query('
            SELECT *
            FROM players 
            WHERE discord = :discordId
        ', [
            ':discordId' => $discordId
        ]);

        if ($query->rowCount() === 0) {
            return false;
        }

        return true;
    }

    /**
     * Check whether the code exists
     *
     * @param string $code
     * @return bool
     */
    protected function doesCodeExist(string $code): bool
    {
        $query = $this->db->query('SELECT * FROM player_link_codes WHERE code = :code', [
            ':code' => $code
        ]);

        $rows = $query->rowCount();

        if ($rows === 0) {
            return false;
        }

        $response = $query->fetch();
        if ($response === false) {
            return false;
        } elseif ($response['expires'] < time()) {
            $this->db->delete('player_link_codes', [
                'code' => $code
            ]);

            return false;
        }

        return $rows > 0;
    }

    /**
     * Process discord link
     *
     * @param string $steamId
     * @param string $discordId
     * @param string $code
     * @return bool
     */
    public function processDiscordLink(string $steamId, string $discordId, string $code): bool
    {
        if ($this->doesCodeExist($code) && $this->checkDiscordLink($discordId, $code)) {
            $update = $this->db->update('players', [
                'discord' => $discordId
            ], [
                'steam64' => $steamId
            ]);

            if ($update->execute()) {
                return !!$this->db->delete('player_link_codes', [
                    'code' => $code
                ]);
            }
        }

        return false;
    }

    /**
     * Return whether the discord link is valid and in date.
     *
     * @param string $discordId
     * @param string $code
     * @return bool
     */
    protected function checkDiscordLink(string $discordId, string $code): bool
    {
        $query = $this->db->query('
            SELECT expires
            FROM player_link_codes 
            WHERE discord = :discordId AND code = :code
        ', [
            ':code' => $code,
            ':discordId' => $discordId
        ]);

        if ($query->rowCount() === 0) {
            return false;
        }

        return true;
    }
}
