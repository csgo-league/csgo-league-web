<?php

namespace B3none\League\Helpers;

class DiscordHelper extends BaseHelper
{
    /**
     * @var CodeHelper
     */
    protected $code;

    public function __construct()
    {
        parent::__construct();

        $this->code = new CodeHelper();
    }

    public function generateDiscordLinkCode(string $steamId, string $discordId): ?string
    {
        $attempts = 0;
        $length = 5;
        do {
            $code = $this->code->generate($length);

            if (++$attempts == 3) {
                $length += 1;
                $attempts = 0;
            }
        } while ($this->doesCodeExist($code));

        $this->db->insert('player_link_codes', [
            'steam' => $steamId,
            'discordId' => $discordId,
            'code' => $code
        ]);

        return $code;
    }

    protected function doesCodeExist(string $code): bool
    {
        $query = $this->db->query('SELECT * FROM player_link_codes WHERE code = :code', [
            ':code' => $code
        ]);

        return $query->rowCount() > 0;
    }

    protected function checkDiscordLink(string $discordId, string $code)
    {

    }
}
