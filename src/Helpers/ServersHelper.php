<?php

namespace B3none\League\Helpers;

use B3none\ServerDetails\Client as ServerDetails;
use Exception;

class ServersHelper extends BaseHelper
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
                $serverBots = $server->getBots();

                $realPlayers = ($serverPlayers - $serverBots);

                $serverArray = [
                    'players' => $realPlayers,
                    'ip' => $ip,
                    'port' => $port,
                ];
                
                $query = $this->db->query('
                    SELECT *
                    FROM matches
                    WHERE end_time IS NULL
                    AND matches.server_ip = :ip
                    AND matches.server_port = :port
                ', [
                    ':ip' => $ip,
                    ':port' => $port,
                ]);

                if (!$empty) {
                    $response[] = $serverArray;
                } elseif ($realPlayers === 0 && $query->rowCount() === 0) {
                    $response[] = $serverArray;
                    break;
                }
            }
        }

        return $response;
    }
}
