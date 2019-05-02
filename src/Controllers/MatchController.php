<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\MatchHelper;

class MatchController
{
    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var MatchHelper
     */
    protected $matchHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        $this->matchHelper = new MatchHelper();
    }

    /**
     * @param string $matchId
     */
    public function getMatch(string $matchId)
    {

    }
}