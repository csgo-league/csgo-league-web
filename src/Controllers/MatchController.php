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

    public function startMatch(string $ip, string $port)
    {
        $input = input()->all();

        if (!array_key_exists('team_one', $input)) {
            return json_encode([
                'success' => false,
                'error' => 'team_one_missing'
            ]);
        }

        if (!array_key_exists('team_one', $input)) {
            return json_encode([
                'success' => false,
                'error' => 'team_two_missing'
            ]);
        }

        return json_encode(
            $this->matchHelper->startMatch($ip, $port, $input['team_one'], $input['team_two'])
        );
    }

    public function getMatch(string $matchId)
    {
        return json_encode(
            $this->matchHelper->getMatch($matchId)
        );
    }
}
