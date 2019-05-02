<?php

namespace Redline\League\Helpers;

class MatchesHelper extends BaseHelper
{
    protected $table = 'sql_matches_scoretotal';

    public function getMatches(int $page = 1): array
    {
        try {
            $query = $this->db->query("SELECT * FROM {$this->table}");
        } catch (\Exception $e) {

        }

        return $query;
    }
}