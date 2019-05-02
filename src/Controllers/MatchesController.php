<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\MatchesHelper;

class MatchesController
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
        $this->matchesHelper = new MatchesHelper();
    }

    /**
     * @param null|string $page
     */
    public function getIndex(?string $page = null)
    {
        if (isset($_GET['page'])) {
            $page_number = $conn->real_escape_string($_GET['page']);
            $offset = ($page_number - 1) * $limit;
            $sql = "SELECT * FROM sql_matches_scoretotal ORDER BY match_id DESC LIMIT {$offset}, {$limit}";
        } else {
            $page_number = 1;
            $sql = "SELECT * FROM sql_matches_scoretotal ORDER BY match_id DESC LIMIT {$limit}";
        }
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