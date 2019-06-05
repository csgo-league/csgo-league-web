<?php

namespace B3none\League\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware
{
    /**
     * Make sure that the request has been authenticated with the API key
     *
     * @param Request $request
     */
    public function handle(Request $request): void
    {
        $key = $request->getHeader('authentication');

        if ($key != env('API_KEY')) {
            die(
                json_encode([
                    'success' => false,
                    'error' => 'invalid_api_key'
                ])
            );
        }
    }
}