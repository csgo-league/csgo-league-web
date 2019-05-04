<?php

namespace Redline\League\Helpers;

class MatchesHelper extends BaseHelper
{
    protected $table = 'sql_matches_scoretotal';

    public function getMatches(int $page = 1): array
    {
        try {
            return $this->db->query("SELECT * FROM {$this->table} LIMIT {$page}")->fetchAll();
        } catch (\Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            die(json_encode(['status' => 500]));
        }
    }
}