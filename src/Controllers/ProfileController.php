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
    public function getAvatar(string $steamId): void
    {
        $avatar = __DIR__ . "/../../app/cache/steam/$steamId.jpg";
        $fp = fopen($avatar, 'rb');

        // send the right headers
        header('Content-Type: image/jpg');
        header('Content-Length: ' . filesize($avatar));

        // dump the picture and stop the script
        fpassthru($fp);
    }

    /**
     * @param string $steamId
     */
    public function getProfile(string $steamId): void
    {
//        echo json_encode($this->profileHelper->cacheProfileDetails($steamId));
        response()->redirect('https://steamcommunity.com/profiles/' . $steamId);
    }
}