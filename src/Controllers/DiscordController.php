<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\DiscordHelper;

class DiscordController extends BaseController
{
    protected $discordHelper;

    public function __construct()
    {
        parent::__construct();

        $this->discordHelper = new DiscordHelper();
    }

    /**
     * Link Discord
     *
     * @param string $discordId
     * @param string $token
     * @return void
     */
    public function linkDiscord(string $discordId, string $token): void
    {
        try {
            if (!$this->steam->loggedIn()) {
                response()->redirect($this->steam->loginUrl());

                return;
            }

            $steamId = $this->authorisedUser['steamid'];
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }

    public function generateDiscordLink(string $discordId)
    {
        try {
            if (!$this->steam->loggedIn()) {
                response()->redirect($this->steam->loginUrl());

                return;
            }

            $steamId = $this->authorisedUser['steamid'];

            $this->discordHelper->generateDiscordLinkCode($steamId, $discordId);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }
}
