<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
use B3none\League\Helpers\MatchHelper;
use Exception;
use Reflex\Rcon\Exceptions\NotAuthenticatedException;
use Reflex\Rcon\Exceptions\RconAuthException;
use Reflex\Rcon\Exceptions\RconConnectException;

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
            return ExceptionHelper::handle($exception);
        }
    }

    /**
     * Start a match
     *
     * @return string
     * @throws NotAuthenticatedException
     * @throws RconAuthException
     * @throws RconConnectException
     */
    public function startMatch(): string
    {
        $input = input()->all();

        if (!array_key_exists('team_one', $input)) {
            return response()->httpCode(422)->json([
                'success' => false,
                'error' => 'team_one_missing'
            ]);
        } elseif (!array_key_exists('team_two', $input)) {
            return response()->httpCode(422)->json([
                'success' => false,
                'error' => 'team_two_missing'
            ]);
        }

        $teamOne = $input['team_one'];
        $teamTwo = $input['team_two'];

        return response()->json(
            $this->matchHelper->startMatch($teamOne, $teamTwo)
        );
    }

    /**
     * @param string $matchId
     * @return string
     */
    public function getMatch(string $matchId): string
    {
        return response()->json(
            $this->matchHelper->getMatch($matchId)
        );
    }

    /**
     * @param string $matchId
     * @return string
     */
    public function endMatch(string $matchId): string
    {
        $input = input()->all();

        if (empty($input['ip'])) {
            return response()->json([
                'success' => false,
                'error' => 'ip_empty'
            ]);
        } elseif (!array_key_exists('port', $input)) {
            return response()->json([
                'success' => false,
                'error' => 'port_missing'
            ]);
        } elseif (empty($input['port'])) {
            return response()->json([
                'success' => false,
                'error' => 'port_empty'
            ]);
        }

        $ip = $input['ip'];
        $port = $input['port'];

        return response()->json(
            $this->matchHelper->endMatch($matchId, $ip, $port)
        );
    }
}
