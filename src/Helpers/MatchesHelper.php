<?php

namespace Redline\League\Helpers;

class MatchesHelper extends BaseHelper
{
    protected $table = 'sql_matches';

    protected function getMatches(int $page = 1): array
    {
        return $this->db->query('SELECT * FROM sql_matches_scoretotal ORDER BY match_id DESC LIMIT ?, 10', [
            $page - 1,
        ]);
    }
}