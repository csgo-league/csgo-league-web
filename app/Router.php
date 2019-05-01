<?php

use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\Http\Request;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter;

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
            '/match',
            '/profile'
        ];
        foreach ($homeRedirects as $homeRedirect) {
            SimpleRouter::get($homeRedirect, function () {
                response()->redirect('/');
            });
        }

        SimpleRouter::controller('/matches', MatchesController::class);
        SimpleRouter::get('/match/{matchId}', MatchController::class . '@getMatch');
        SimpleRouter::get('/profile/{steamId}', ProfileController::class . '@getProfile');

//        SimpleRouter::get('/errors/{errors}', ErrorController::class . '@returnError');
//        SimpleRouter::controller('/search', SearchController::class);

        // Anything that's not registered fallback to the homepage.
        SimpleRouter::error(function(Request $request, \Exception $exception) {
            response()->redirect('https://redlinecs.net');
        });
    }
}