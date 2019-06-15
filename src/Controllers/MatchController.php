<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
use B3none\League\Helpers\MatchHelper;
use Exception;

class MatchController extends BaseController
{
    /**
     * @var MatchHelper
     */
    protected $matchHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->matchHelper = new MatchHelper();
    }

    /**
     * @param string $matchId
     * @return string
     */
    public function getMatchView(string $matchId): string
    {
        try {
            if ($matchId != (int)$matchId) {
                throw new Exception('Please only pass an integer to the matchId value.');
            }

            $match = $this->matchHelper->getMatchPlayers($matchId);

            if ($match === null) {
                response()->redirect('/matches');

                die;
            }

            return $this->twig->render('match.twig', array_merge($match, [
                'nav' => [
                    'active' => 'matches',
                    'loggedIn' => $this->steam->loggedIn(),
                    'user' => $this->authorisedUser,
                    'discordInviteLink' => env('DISCORD')
                ],
                'baseTitle' => env('BASE_TITLE'),
                'description' => env('DESCRIPTION'),
                'title' => 'Match',
            ]));
        } catch (Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }

    /**
     * Start a match
     *
     * @param string $ip
     * @param string $port
     * @return false|string
     */
    public function startMatch()
    {
        $input = input()->all();

        if (!array_key_exists('team_one', $input)) {
            return json_encode([
                'success' => false,
                'error' => 'team_one_missing'
            ]);
        } elseif (!array_key_exists('team_two', $input)) {
            return json_encode([
                'success' => false,
                'error' => 'team_two_missing'
            ]);
        } elseif (!array_key_exists('ip', $input)) {
            return json_encode([
                'success' => false,
                'error' => 'ip_missing'
            ]);
        } elseif (empty($input['ip'])) {
            return json_encode([
                'success' => false,
                'error' => 'ip_empty'
            ]);
        } elseif (!array_key_exists('port', $input)) {
            return json_encode([
                'success' => false,
                'error' => 'port_missing'
            ]);
        } elseif (empty($input['port'])) {
            return json_encode([
                'success' => false,
                'error' => 'port_empty'
            ]);
        }

        $ip = $input['ip'];
        $port = $input['port'];

        $teamOne = $input['team_one'];
//        if (count($teamOne) != 5) {
//            return json_encode([
//                'success' => false,
//                'error' => 'team_one_wrong_size'
//            ]);
//        }

        $teamTwo = $input['team_two'];
//        if (count($teamTwo) != 5) {
//            return json_encode([
//                'success' => false,
//                'error' => 'team_two_wrong_size'
//            ]);
//        }

        return json_encode(
            $this->matchHelper->startMatch($ip, $port, $teamOne, $teamTwo)
        );
    }

    /**
     * @param string $matchId
     * @return false|string
     */
    public function getMatch(string $matchId)
    {
        return json_encode(
            $this->matchHelper->getMatch($matchId)
        );
    }
}
