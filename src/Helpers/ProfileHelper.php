<?php

namespace Redline\League\Helpers;


class ProfileHelper extends BaseHelper
{
    /**
     * @var string
     */
    protected $steamApiKey;

    public function __construct()
    {
        parent::__construct();

        $this->steamApiKey = env('STEAM_API');
    }

    /**
     * @param string $steamId
     * @return array
     */
    public function cacheProfileDetails(string $steamId): array
    {
        $steam_api = $this->steamApiKey;
        $cacheDir = __DIR__ . '/../../web/cache';

        if (file_exists("$cacheDir/$steamId.json")) {
            $json_file = file_get_contents("$cacheDir/$steamId.json");
            $response = json_decode($json_file, true);

            if (strtotime($response['cachecreated']) < strtotime('1 month ago')) {
                unlink("$cacheDir/$steamId.jpg");
                unlink("$cacheDir/$steamId.json");
            }
        } else {
            $api_url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$steam_api&steamids=$steamId";
            $steam = json_decode(file_get_contents($api_url), true);
            if ($steam['response']['players'] != null) {
                $response = [
                    'steamid64' => $steam['response']['players'][0]['steamid'],
                    'name' => $steam['response']['players'][0]['personaname'],
                    'url' => $steam['response']['players'][0]['profileurl'],
                    'avatar' => "$steamId.jpg",
                    'cachecreated' => date('Y-m-d'),
                ];

                $this->db->update('players', [
                    'name' => $steam['response']['players'][0]['personaname']
                ], [
                    'steamid64' => $steam['response']['players'][0]['steamid']
                ]);

                $avatar = file_get_contents($steam['response']['players'][0]['avatarfull']);
                file_put_contents("$cacheDir/$steamId.jpg", $avatar);

                $cache_file = fopen("$cacheDir/$steamId.json", 'w');
                fwrite($cache_file, json_encode($response));
                fclose($cache_file);
            } else {
                $response = 0;
            }
        }

        return $response;
    }
}