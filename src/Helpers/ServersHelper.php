<?php

namespace B3none\League\Helpers;

use B3none\ServerDetails\Client as ServerDetails;
use Exception;

class ServersHelper
{
    /**
     * @var ServerDetails
     */
    protected $serverDetails;

    public function __construct()
    {
        $this->serverDetails = ServerDetails::create();
    }

    /**
     * Get servers
     *
     * @param bool $empty
     * @return array
     */
    public function getServers(bool $empty = true): array
    {
        $servers = explode(',', env('SERVERS'));
        $response = [];

        foreach ($servers as $connect) {
            list($ip, $port) = explode(':', $connect);
            try {
                $server = $this->serverDetails->getServer($ip, $port);
            } catch (Exception $exception) {
                $server = null;
            }

            if ($server !== null) {
                $serverPlayers = $server->getPlayers();

                $serverArray = [
                    'players' => $serverPlayers,
                    'server' => $connect
                ];

                if (!$empty) {
                    $response[] = $serverArray;
                } elseif ($serverPlayers === 0) {
                    $response[] = $serverArray;
                }
            }
        }

        return $response;
    }
}
