<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
use B3none\League\Helpers\SteamHelper;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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
            $loader = new FilesystemLoader(__DIR__ . '/../Views');

            $remote = $_SERVER['REMOTE_ADDR'];
            if ($remote === '127.0.0.1' || $remote === '::1') {
                $port = $_SERVER['SERVER_PORT'];

                $this->twig = new Environment($loader);

                $this->steam = new SteamHelper([
                    'apikey' => env('STEAM_API_KEY'),
                    'domainname' => "http://localhost:$port",
                    'loginpage' => "http://localhost:$port/home",
                    'logoutpage' => "http://localhost:$port/home",
                    'skipAPI' => true,
                ]);
            } else {
                $this->twig = new Environment($loader, [
                    'cache' => __DIR__ . '/../../app/cache/twig',
                ]);

                $this->steam = SteamHelper::getSteamHelper();
            }

            $this->authorisedUser = $this->steam->getAuthorisedUser();
        } catch (\Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }
}
