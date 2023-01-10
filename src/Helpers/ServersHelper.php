<?php

namespace B3none\League\Helpers;

use Exception;
use xPaw\SourceQuery\SourceQuery;

class ServersHelper
{
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
            [$ip, $port] = explode(':', $connect);

            $didConnect = true;
            $serverPlayers = 0;

            $query = new SourceQuery();

            try {
                $query->Connect($ip, (int) $port);

                $serverPlayers = $query->GetPlayers();
            } catch(Exception $e) {
                $didConnect = false;
            } finally {
                $query->Disconnect();
            }

            if ($didConnect) {
                $serverArray = [
                    'players' => $serverPlayers,
                    'ip' => $ip,
                    'port' => $port,
                ];

                if (!$empty) {
                    $response[] = $serverArray;
                } elseif ($serverPlayers === 0) {
                    $response[] = $serverArray;
                    break;
                }
            }
        }

        return $response;
    }
}
