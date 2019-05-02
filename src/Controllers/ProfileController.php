<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\ProfileHelper;

class ProfileController
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
        $this->profileHelper = new ProfileHelper();
    }

    public function getProfile(string $steamId)
    {

    }
}