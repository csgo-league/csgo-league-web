<?php

namespace B3none\League\Controllers;

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
    public function getMatch(string $matchId): string
    {
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
                'user' => $this->authorisedUser
            ],
            'baseTitle' => env('BASE_TITLE'),
            'description' => env('DESCRIPTION'),
            'title' => 'Match',
        ]));
    }
}
