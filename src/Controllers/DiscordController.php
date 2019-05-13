<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\DiscordHelper;

class DiscordController extends BaseController
{
    /**
     * @var DiscordHelper
     */
    protected $discordHelper;

    /**
     * DiscordController constructor.
     */
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
     * @return string
     */
    public function linkDiscord(string $discordId, string $code): string
    {
        try {
            if (!$this->steam->loggedIn()) {
                return "<a href=\"{$this->steam->loginUrl()}\">Login with Steam</a> and try again.";
            }

            $steamId = $this->authorisedUser['steamid'];

            if ($this->discordHelper->processDiscordLink($steamId, $discordId, $code)) {
                return "Success! You may now close this window.";
            }

            return "Failure! If you're really struggling contact B3none";
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
