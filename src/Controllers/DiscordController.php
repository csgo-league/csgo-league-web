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
     * @param string $code
     * @return void
     */
    public function linkDiscord(string $discordId, string $code): void
    {
        try {
            if (!$this->steam->loggedIn()) {
                response()->redirect($this->steam->loginUrl());

                return;
            }

            $steamId = $this->authorisedUser['steamid'];

            $this->discordHelper->processDiscordLink($steamId, $discordId, $code);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }

    /**
     * Generate discord link
     *
     * @param string $discordId
     * @return string
     */
    public function generateDiscordLink(string $discordId): string
    {
        try {
            return json_encode($this->discordHelper->generateDiscordLinkCode($discordId));
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }
}
