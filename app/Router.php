<?php

use B3none\League\Controllers\DiscordController;
use B3none\League\Controllers\LoginController;
use B3none\League\Controllers\PlayerController;
use B3none\League\Controllers\ServersController;
use B3none\League\Helpers\ExceptionHelper;
use B3none\League\Middleware\AuthMiddleware;
use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\Http\Request;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter as Route;
use B3none\League\Controllers\HomeController;
use B3none\League\Controllers\MatchController;
use B3none\League\Controllers\MatchesController;
use B3none\League\Controllers\PlayersController;
use B3none\League\Controllers\ProfileController;

class Router
{
    /**
     * Initialise the routes.
     */
    public function initialiseRoutes()
    {
        // Load all of the necessary routes
        $this->registerRoutes();

        // Start the routing
        try {
            Route::start();
        } catch (TokenMismatchException $exception) {
            ExceptionHelper::handle($exception);
        } catch (NotFoundHttpException $exception) {
            ExceptionHelper::handle($exception);
        } catch (HttpException $exception) {
            ExceptionHelper::handle($exception);
        } catch (Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }

    /**
     * Register the routes.
     */
    protected function registerRoutes()
    {
        // Redirect to the homepage.
        $homeRedirects = [
            '/',
            '/match',
            '/profile',
            '/discord',
        ];
        foreach ($homeRedirects as $homeRedirect) {
            Route::get($homeRedirect, function () {
                response()->redirect('/home');
            });
        }

        // Get home
        Route::get('/home', HomeController::class . '@getIndex');

        // Get matches
        Route::get('/matches', MatchesController::class . '@getIndex');
        Route::get('/matches/{page}', MatchesController::class . '@getIndex');

        // Search matches
        Route::post('/matches', MatchesController::class . '@postIndex');
        Route::post('/matches/{page}', MatchesController::class . '@postIndex');

        // Get players
        Route::get('/players', PlayersController::class . '@getPlayers');
        Route::get('/players/{page}', PlayersController::class . '@getPlayers');

        // Search players
        Route::post('/players', PlayersController::class . '@postIndex');
        Route::post('/players/{page}', PlayersController::class . '@postIndex');

        // Get match
        Route::get('/match/{matchId}', MatchController::class . '@getMatchView');

        // Get profile
        Route::get('/profile/{steamId}', ProfileController::class . '@getProfile');

        // Log in & log out
        Route::get('/login', LoginController::class . '@login');
        Route::get('/logout', LoginController::class . '@logout');

        // Routes which require authentication
        Route::group(['middleware' => AuthMiddleware::class], function () {
            // Authorised discord endpoints
            Route::get('/discord/generate/{discordId}', DiscordController::class . '@generateDiscordLink');
            Route::post('/discord/update/{discordId}', DiscordController::class . '@updateName');
            Route::get('/discord/check/{discordId}', DiscordController::class . '@checkDiscordLink');
            Route::get('/discord/name/{discordId}', DiscordController::class . '@getName');

            // Authorised player endpoints
            Route::get('/player/discord/{discordId}', PlayerController::class . '@getPlayerByDiscordId');

            // Authorised server endpoints
            Route::get('/servers', ServersController::class . '@getServers');

            // Authorised match endpoints
            Route::post('/match/start', MatchController::class . '@startMatch');
            Route::get('/match/end/{matchId}', MatchController::class . '@endMatch');
        });

        // Get a match's JSON file.
        Route::get('/match/get/{matchId}', MatchController::class . '@getMatch');

        // Link discord
        Route::get('/discord/{discordId}/{code}', DiscordController::class . '@linkDiscord');

        // Anything that's not registered fallback to the homepage.
        Route::error(function(Request $request, Exception $exception) {
            $remote = $_SERVER['REMOTE_ADDR'];

            if ($remote !== '127.0.0.1' && $remote !== '::1') {
                response()->redirect(env('WEBSITE') ?? 'https://b3none.co.uk');
            } else {
                ExceptionHelper::handle($exception);
            }
        });
    }
}
