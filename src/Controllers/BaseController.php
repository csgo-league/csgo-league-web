<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
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
        try {
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../Views');

            $remote = $_SERVER['REMOTE_ADDR'];
            if ($remote === '127.0.0.1' || $remote === '::1') {
                $this->twig = new Environment($loader);

                $this->steam = new SteamHelper([
                    'apikey' => env('STEAM_API_KEY'),
                    'domainname' => 'http://localhost:5000',
                    'loginpage' => 'http://localhost:5000/home',
                    'logoutpage' => 'http://localhost:5000/home',
                    'skipAPI' => true,
                ]);
            } else {
                $this->twig = new Environment($loader, [
                    'cache' => __DIR__ . '/../../app/cache/twig',
                ]);

                $this->steam = new SteamHelper([
                    'apikey' => env('STEAM_API_KEY'), // Steam API KEY
                    'domainname' => 'https://league.redlinecs.net', // Displayed domain in the login-screen
                    'loginpage' => 'https://league.redlinecs.net/home', // Returns to last page if not set
                    'logoutpage' => 'https://league.redlinecs.net/home',
                    'skipAPI' => true, // true = don't get the data from steam, just return the steam64
                ]);
            }

            $this->authorisedUser = $this->steam->getAuthorisedUser();
        } catch (\Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }
}
