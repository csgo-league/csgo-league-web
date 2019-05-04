<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\MatchesHelper;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MatchesController extends BaseController
{
    /**
     * @var MatchesHelper
     */
    protected $matchesHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->matchesHelper = new MatchesHelper();
    }

    /**
     * @param null|string $page
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getIndex(?string $page = null): string
    {
        $page = $page ?? 1;
        $matches = $this->matchesHelper->getMatches($page);
        $totalMatches = $this->matchesHelper->getMatchesCount();

        return $this->twig->render('matches.twig', [
            'matches' => $matches,
            'page' => $page,
            'total_pages' => $totalMatches / env('LIMIT')
        ]);
    }

    public function postIndex(string $search)
    {
        if (isset($_POST['Submit']) && !empty($_POST['search-bar'])) {
            $search = $conn->real_escape_string($_POST['search-bar']);
            $sql = "SELECT DISTINCT sql_matches_scoretotal.match_id, sql_matches_scoretotal.map, sql_matches_scoretotal.team_2, sql_matches_scoretotal.team_3
                FROM sql_matches_scoretotal INNER JOIN sql_matches
                ON sql_matches_scoretotal.match_id = sql_matches.match_id
                WHERE sql_matches.name LIKE '%".$search."%' OR sql_matches.steamid64 = '".$search."' OR sql_matches_scoretotal.match_id = '".$search."' ORDER BY sql_matches_scoretotal.match_id DESC";
        }
    }
}