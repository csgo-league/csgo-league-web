<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\DiscordHelper;
use B3none\League\Helpers\ExceptionHelper;
use Exception;

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
        if (!$this->steam->loggedIn()) {
            return "<a href=\"{$this->steam->loginUrl()}\">Login with Steam</a> and visit this URL again.";
        }

        $steamId = $this->authorisedUser['steamid'];

        if ($this->discordHelper->processDiscordLink($steamId, $discordId, $code)) {
            return 'Success! You may now close this window.';
        }

        return 'Failure! If you\'re really struggling contact B3none';
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
            return json_encode(
                $this->discordHelper->generateDiscordLinkCode($discordId)
            );
        } catch (Exception $e) {
            ExceptionHelper::handle($e);
        }
    }

    /**
     * Update a linked users name
     *
     * @param string $discordId
     * @return string
     */
    public function updateName(string $discordId): string
    {
        $input = input()->all();

        if (!array_key_exists('discord_name', $input)) {
            return json_encode([
                'success' => false,
                'error' => 'no_name_passed'
            ]);
        }

        return json_encode(
            $this->discordHelper->updateName($discordId, $input['discord_name'])
        );
    }

    /**
     * Check whether the user is linked on the system
     *
     * @param string $discordId
     * @return string
     */
    public function checkDiscordLink(string $discordId): string
    {
        return json_encode(
            $this->discordHelper->checkLink($discordId)
        );
    }

    /**
     * Get a client's name based on their discordId
     *
     * @param string $discordId
     * @return string
     */
    public function getName(string $discordId): string
    {
        return json_encode(
            $this->discordHelper->getName($discordId)
        );
    }
}
