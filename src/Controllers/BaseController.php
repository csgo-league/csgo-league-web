<?php

namespace B3none\League\Controllers;

use Twig\Environment;
use Vikas5914\SteamAuth;

class BaseController
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var SteamAuth
     */
    protected $steam;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../Views');

        if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1') {
            $this->twig = new Environment($loader);
        } else {
            $this->twig = new Environment($loader, [
                'cache' => __DIR__ . '/../../app/cache/twig',
            ]);
        }

        $this->steam = new SteamAuth([
            'apikey' => env('STEAM_API_KEY'), // Steam API KEY
            'domainname' => 'http://localhost:5000', // Displayed domain in the login-screen
            'loginpage' => 'http://localhost:5000', // Returns to last page if not set
            "logoutpage" => '',
            'skipAPI' => true, // true = dont get the data from steam, just return the steamid64
        ]);
    }
}
