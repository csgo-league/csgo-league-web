<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\ProfileHelper;

class ProfileController extends BaseController
{
    /**
     * @var ProfileHelper
     */
    protected $profileHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->profileHelper = new ProfileHelper();
    }

    /**
     * @param string $steamId
     */
    public function getProfile(string $steamId): void
    {
        response()->redirect('https://steamcommunity.com/profiles/' . $steamId);
    }
}