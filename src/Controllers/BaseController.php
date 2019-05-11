<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\SteamHelper;
use Twig\Environment;

class BaseController
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var SteamHelper
     */
    protected $steam;

    /**
     * @var null|array
     */
    protected $authorisedUser = null;

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

        try {
            $this->steam = new SteamHelper([
                'apikey' => env('STEAM_API_KEY'), // Steam API KEY
                'domainname' => 'http://localhost:5000', // Displayed domain in the login-screen
                'loginpage' => 'http://localhost:5000/home', // Returns to last page if not set
                'logoutpage' => 'http://localhost:5000/home',
                'skipAPI' => true, // true = dont get the data from steam, just return the steamid64
            ]);

            $this->authorisedUser = $this->steam->getAuthorisedUser();
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }
}
