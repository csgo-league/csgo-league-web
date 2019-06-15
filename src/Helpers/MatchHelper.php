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
     * MatchHelper constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->codeHelper = new CodeHelper();
    }

    /**
     * @param string $matchId
     * @return array|null
     */
    public function getMatchPlayers(string $matchId): ?array
    {
        $query = $this->db->query('
            SELECT sql_matches_scoretotal.*, sql_matches.*
            FROM sql_matches_scoretotal 
            INNER JOIN sql_matches ON sql_matches_scoretotal.match_id = sql_matches.match_id
            WHERE sql_matches_scoretotal.match_id = :matchId 
            ORDER BY sql_matches.score DESC
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
            if ($player['team'] == 2) {
                $formattedPlayers['ct']['players'][] = $this->formatMatchPlayer($player);
                $formattedPlayers['ct']['score'] = $player['team_2'];

                if (array_key_exists('teamname_2', $player) && !empty($player['teamname_2'])) {
                    $formattedPlayers['ct']['name'] = $player['teamname_2'];
                }
            } elseif ($player['team'] == 3) {
                $formattedPlayers['t']['players'][] = $this->formatMatchPlayer($player);
                $formattedPlayers['t']['score'] = $player['team_3'];

                if (array_key_exists('teamname_3', $player) && !empty($player['teamname_3'])) {
                    $formattedPlayers['t']['name'] = $player['teamname_3'];
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
     * @param string $ip
     * @param string $port
     * @param array $teamOne
     * @param array $teamTwo
     * @return array
     * @throws NotAuthenticatedException
     * @throws RconAuthException
     * @throws RconConnectException
     */
    public function startMatch(string $ip, string $port, array $teamOne, array $teamTwo): array
    {
        $matchId = $this->generateMatch($teamOne, $teamTwo);

        // Execute the config on the server
        $server = new Rcon($ip, $port, env('RCON'));
        $server->connect();

        $server->exec("get5_loadmatch_url " . preg_replace('(^https?://)', '', env('URL')) . "/match/get/$matchId");

        return [
            'match_id' => $matchId
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
     * @return array
     */
    public function endMatch(string $matchId, string $ip, string $port): array
    {
        $server = new Rcon($ip, $port, env('RCON'));
        $server->connect();

        $server->exec('get5_endmatch; map de_mirage');

        $matchConfig = self::MATCHES_CACHE . "/$matchId.json";

        return [
            'success' => unlink($matchConfig)
        ];
    }

    /**
     * Generate a match and return the matchId
     *
     * @param array $teamOne
     * @param array $teamTwo
     * @return int
     */
    protected function generateMatch(array $teamOne, array $teamTwo): int
    {
        $matchId = $this->generateMatchId();

        $matchConfig = __DIR__. '/../../app/cache/matches/' . $matchId . '.json';

        $setup = [
            'matchid' => (string)$matchId,
            'num_maps' => 1,
            'players_per_team' => 5,
            'min_players_to_ready' => 10,
            'min_spectators_to_ready' => 0,
            'skip_veto' => false,
            'veto_first' => 'team1',
            'side_type' => 'always_knife',
            'spectators' => [
                'players' => [],
            ],
            'maplist' => [
                'de_dust2',
                'de_inferno',
                'de_mirage',
                'de_nuke',
                'de_overpass',
                'de_train',
                'de_vertigo',
            ],
            'team1' => [
                'players' => $teamOne,
            ],
            'team2' => [
                'players' => $teamTwo,
            ],
            'cvars' => [
                'hostname' => env('BASE_TITLE') . ' Scrim | github.com/csgo-league',
                'get5_kick_when_no_match_loaded' => 1,
                'get5_print_damage' => 1,
                'get5_check_auths' => 1,
                'league_matches_force_matchid' => $matchId,
                'get5_time_to_start' => 300,
            ],
        ];

        file_put_contents($matchConfig, json_encode($setup));

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

        return $response['match_id'];
    }
}
