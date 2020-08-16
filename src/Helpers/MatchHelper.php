<?php

namespace B3none\League\Helpers;

use Exception;
use Reflex\Rcon\Exceptions\NotAuthenticatedException;
use Reflex\Rcon\Exceptions\RconAuthException;
use Reflex\Rcon\Exceptions\RconConnectException;
use Reflex\Rcon\Rcon;

class MatchHelper extends BaseHelper
{
    const MATCHES_CACHE = __DIR__ . '/../../app/cache/matches';

    /**
     * @var CodeHelper
     */
    protected $codeHelper;

    /**
     * @var ServersHelper
     */
    protected $serversHelper;

    /**
     * @var PlayerHelper
     */
    protected $playerHelper;

    /**
     * MatchHelper constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->codeHelper = new CodeHelper();
        $this->serversHelper = new ServersHelper();
        $this->playerHelper = new PlayerHelper();
    }

    /**
     * @param string $matchId
     * @return array|null
     */
    public function getMatchPlayers(string $matchId): ?array
    {
        $query = $this->db->query('
            SELECT DISTINCT
            matches_maps.*, matches_players.*, matches.team1_name, matches.team2_name
            FROM matches
            LEFT JOIN matches_maps ON matches_maps.matchid = matches.matchid
            LEFT JOIN matches_players ON matches_players.matchid = matches.matchid
            WHERE matches.matchid = :matchId 
            ORDER BY matches_players.playerscore DESC
        ', [
            ':matchId' => $matchId,
        ]);

        $matchPlayers = $query->fetchAll();

        return $matchPlayers ? $this->formatMatchPlayers($matchPlayers) : null;
    }

    /**
     * @param array $players
     * @return array
     */
    protected function formatMatchPlayers(array $players): array
    {
        $formattedPlayers = [
            'ct' => [
                'players' => [],
                'name' => 'Counter-Terrorists',
                'score' => 0
            ],
            't' => [
                'players' => [],
                'name' => 'Terrorists',
                'score' => 0
            ],
        ];

        foreach ($players as $player) {
            if ($player['team'] == 'team2') {
                $formattedPlayers['ct']['players'][] = $this->formatMatchPlayer($player);
                $formattedPlayers['ct']['score'] = $player['team2_score'];

                if (array_key_exists('team2_name', $player) && !empty($player['team2_name'])) {
                    $formattedPlayers['ct']['name'] = $player['team2_name'];
                }
            } elseif ($player['team'] == 'team1') {
                $formattedPlayers['t']['players'][] = $this->formatMatchPlayer($player);
                $formattedPlayers['t']['score'] = $player['team1_score'];

                if (array_key_exists('team1_name', $player) && !empty($player['team1_name'])) {
                    $formattedPlayers['t']['name'] = $player['team1_name'];
                }
            }
        }

        return $formattedPlayers;
    }

    /**
     * @param array $player
     * @return array
     */
    protected function formatMatchPlayer(array $player): array
    {
        if ($player['kills'] > 0 && $player['deaths'] > 0) {
            $player['kdr'] = round(($player['kills'] / $player['deaths']), 2);
        } else {
            $player['kdr'] = 0;
        }

        $player['name'] = htmlspecialchars(substr($player['name'], 0, 32));

        return $player;
    }

    /**
     * @param array $teamOne
     * @param array $teamTwo
     * @return array
     * @throws NotAuthenticatedException
     * @throws RconAuthException
     * @throws RconConnectException
     */
    public function startMatch(array $teamOne, array $teamTwo, array $maps = []): array
    {
        $servers = $this->serversHelper->getServers(true);

        if (!count($servers)) {
            response()->httpCode(500)->json([
                'error' => 'No matches found',
            ]);

            return [];
        }

        $server = $servers[0];
        $matchId = $this->generateMatch($teamOne, $teamTwo, $maps);

        $ip = $server['ip'];
        $port = $server['port'];

        // Execute the config on the server
        $server = new Rcon($ip, $port, env('RCON'));
        $server->connect();

        $server->exec('get5_loadmatch_url ' . preg_replace('(^https?://)', '', env('URL')) . "/match/get/$matchId");

        return [
            'match_id' => $matchId,
            'ip' => $ip,
            'port' => $port,
        ];
    }

    /**
     * Get match data by match Id
     *
     * @param string $matchId
     * @return array
     */
    public function getMatch(string $matchId): array
    {
        $matchConfig = self::MATCHES_CACHE . "/$matchId.json";

        if (!file_exists($matchConfig)) {
            return [
                'error' => 'not_found'
            ];
        }

        $matchConfig = file_get_contents($matchConfig);
        return json_decode($matchConfig, true);
    }

    /**
     * Get match data by match Id
     *
     * @param string $matchId
     * @param string $ip
     * @param string $port
     * @return array
     * @throws NotAuthenticatedException
     * @throws RconAuthException
     * @throws RconConnectException
     */
    public function endMatch(string $matchId): array
    {
        $query = $this->db->query('
            SELECT
            end_time,
            server_ip,
            server_port
            FROM matches
            WHERE matches.matchid = :matchId
        ', [
            ':matchId' => $matchId,
        ]);

        $match = $query->fetch();

        if (!$match) {
            return [
                'success' => false,
                'error' => 'Match not found',
            ];
        }

        if ($match['end_time']) {
            return [
                'success' => false,
                'error' => 'Match is already over',
            ];
        }

        if (!array_key_exists('server_ip', $match) || !$match['server_ip']) {
            return [
                'success' => false,
                'error' => 'server_ip does not exist or is not valid',
            ];
        }

        if (!array_key_exists('server_port', $match) || !$match['server_port']) {
            return [
                'success' => false,
                'error' => 'server_port does not exist or is not valid',
            ];
        }

        $server = new Rcon($match['server_ip'], $match['server_port'], env('RCON'));
        $server->connect();

        $server->exec('get5_endmatch; map de_mirage');

        $matchConfig = self::MATCHES_CACHE . "/$matchId.json";

        return [
            'success' => unlink($matchConfig),
        ];
    }

    /**
     * Generate a match and return the matchId
     *
     * @param array $teamOne
     * @param array $teamTwo
     * @param array $maps
     * @return int
     */
    protected function generateMatch(array $teamOne, array $teamTwo, array $maps = []): int
    {
        $matchId = $this->generateMatchId();

        $matchConfig = __DIR__. '/../../app/cache/matches/' . $matchId . '.json';

        $totalPlayers = count($teamOne) + count($teamTwo);

        foreach ($teamOne as $discordId => $name) {
            $player = $this->playerHelper->getPlayerByDiscordId($discordId);

            unset($teamOne[$discordId]);

            $this->db->insert('matches_players', [
                'matchid' => $matchId,
                'steam' => $player['steam'],
            ]);

            $teamOne[$player['steam']] = $name;
        }

        foreach ($teamTwo as $discordId => $name) {
            $player = $this->playerHelper->getPlayerByDiscordId($discordId);

            unset($teamTwo[$discordId]);

            $this->db->insert('matches_players', [
                'matchid' => $matchId,
                'steam' => $player['steam'],
            ]);

            $teamTwo[$player['steam']] = $name;
        }

        $hasMaps = count($maps) > 0;

        $setup = [
            'matchid' => (string)$matchId,
            'num_maps' => $hasMaps ? count($maps) : 1,
            'players_per_team' => ceil($totalPlayers / 2),
            'min_players_to_ready' => $totalPlayers,
            'min_spectators_to_ready' => 0,
            'skip_veto' => $hasMaps,
            'veto_first' => 'team1',
            'side_type' => 'always_knife',
            'spectators' => [
                'players' => [],
            ],
            'maplist' => $hasMaps ? $maps : [
                'de_dust2',
                'de_inferno',
                'de_mirage',
                'de_nuke',
                'de_cache',
                'de_overpass',
                'de_train',
            ],
            'team1' => [
                'players' => $teamOne,
            ],
            'team2' => [
                'players' => $teamTwo,
            ],
            'cvars' => [
                'hostname' => env('BASE_TITLE') . ' Scrim | github.com/csgo-league',
                'get5_kick_when_no_match_loaded' => '1',
                'league_matches_force_matchid' => (string)$matchId,
                'get5_time_to_start' => '300',
            ],
        ];

        file_put_contents($matchConfig, json_encode($setup, JSON_PRETTY_PRINT));

        return $matchId;
    }

    /**
     * Generate MatchId
     *
     * @return int
     */
    protected function generateMatchId(): int
    {
        $matchId = $this->getMostRecentMatchId();
        while ($this->doesMatchIdExist($matchId)) {
            $matchId++;
        }

        return $matchId;
    }

    /**
     * Check whether the given matchId exists.
     *
     * @param int $matchId
     * @return bool
     */
    protected function doesMatchIdExist(int $matchId): bool
    {
        // Check whether the matchId already exists in the database.
        $query = $this->db->query('SELECT matches.matchid FROM matches WHERE matchid = :matchId', [
            ':matchId' => $matchId
        ]);

        $matches = array_filter(scandir(self::MATCHES_CACHE), function ($item) {
            return !is_dir(self::MATCHES_CACHE . "/$item");
        });

        return $query->rowCount() !== 0 || in_array("$matchId.json", $matches);
    }

    /**
     * Get the most recent matchId
     *
     * @return int
     */
    protected function getMostRecentMatchId(): int
    {
        $query = $this->db->query('SELECT matchid FROM matches ORDER BY matchid DESC LIMIT 1');

        $response = $query->fetch() ?: [
            'match_id' => 1
        ];

        return $response['match_id'] ?? 1;
    }
}
