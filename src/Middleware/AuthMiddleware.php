<?php

namespace B3none\League\Middleware;

use Pecee\Http\Middleware\IMiddleware as BaseMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements BaseMiddleware
{
    protected $apiKeys = [];

    /**
     * AuthMiddleware constructor.
     */
    public function __construct()
    {
        $this->apiKeys = explode(',', env('API_KEYS'));
    }

    /**
     * Make sure that the request has been authenticated with a valid API Key
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