<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
use B3none\League\Helpers\ServersHelper;
use Exception;

class ServersController extends BaseController
{
    /**
     * @var ServersHelper
     */
    protected $serversHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->serversHelper = new ServersHelper();
    }

    /**
     * Get players.
     *
     * @return string
     */
    public function getServers(): string
    {
        try {
            $servers = $this->serversHelper->getServers();

            return json_encode(
                $servers
            );
        } catch (Exception $exception) {
            return ExceptionHelper::handle($exception);
        }
    }
}
