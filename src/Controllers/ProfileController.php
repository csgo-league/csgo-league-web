<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\PlayersHelper;
use Redline\League\Helpers\ProfileHelper;

class ProfileController extends BaseController
{
    /**
     * @var ProfileHelper
     */
    protected $profileHelper;

    /**
     * @var PlayersHelper
     */
    protected $playersHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->profileHelper = new ProfileHelper();
        $this->playersHelper = new PlayersHelper();
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
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getProfile(string $steamId): string
    {
//        echo json_encode($this->profileHelper->cacheProfileDetails($steamId));
        $player = $this->playersHelper->getPlayer($steamId);

        return $this->twig->render('profile.twig', [
            'player' => $player
        ]);
//        response()->redirect('https://steamcommunity.com/profiles/' . $steamId);
    }
}