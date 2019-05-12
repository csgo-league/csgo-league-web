<?php

namespace B3none\League\Controllers;

class DiscordController extends BaseController
{
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
            response()->redirect($this->steam->loginUrl());
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }
}
