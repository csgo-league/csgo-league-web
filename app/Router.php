<?php

use B3none\League\Controllers\DiscordController;
use B3none\League\Controllers\LoginController;
use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\Http\Request;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter;
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
            SimpleRouter::start();
        } catch (TokenMismatchException $e) {
            die($e->getMessage());
        } catch (NotFoundHttpException $e) {
            die($e->getMessage());
        } catch (HttpException $e) {
            die($e->getMessage());
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
    /**
     * Register the routes.
     */
    protected function registerRoutes()
    {
        // Redirect to the leaderboard homepage.
        $homeRedirects = [
            '/',
            '/match',
            '/profile'
        ];
        foreach ($homeRedirects as $homeRedirect) {
            SimpleRouter::get($homeRedirect, function () {
                response()->redirect('/home');
            });
        }

        // Get home
        SimpleRouter::get('/home', HomeController::class . '@getIndex');

        // Get matches
        SimpleRouter::get('/matches', MatchesController::class . '@getIndex');
        SimpleRouter::get('/matches/{page}', MatchesController::class . '@getIndex');

        // Search matches
        SimpleRouter::post('/matches', MatchesController::class . '@postIndex');
        SimpleRouter::post('/matches/{page}', MatchesController::class . '@postIndex');

        // Get players
        SimpleRouter::get('/players', PlayersController::class . '@getPlayers');
        SimpleRouter::get('/players/{page}', PlayersController::class . '@getPlayers');

        // Search players
        SimpleRouter::post('/players', PlayersController::class . '@postIndex');
        SimpleRouter::post('/players/{page}', PlayersController::class . '@postIndex');

        // Get match
        SimpleRouter::get('/match/{matchId}', MatchController::class . '@getMatch');

        // Get profile
        SimpleRouter::get('/profile/{steamId}', ProfileController::class . '@getProfile');

        // Log in to steam
        SimpleRouter::get('/login', LoginController::class . '@login');

        // Link discord
        SimpleRouter::get('/linkDiscord/{discordId}/{code}', DiscordController::class . '@linkDiscord');
        SimpleRouter::get('/generate/{discordId}', DiscordController::class . '@generateDiscordLink');

        // Log out of steam
        SimpleRouter::get('/logout', LoginController::class . '@logout');

        // Anything that's not registered fallback to the homepage.
        SimpleRouter::error(function(Request $request, \Exception $exception) {
//            response()->redirect('https://redlinecs.net');
        });
    }
}
