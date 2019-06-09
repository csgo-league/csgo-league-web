<?php

namespace B3none\League\Middleware;

use Pecee\Http\Middleware\IMiddleware as BaseMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements BaseMiddleware
{
    /**
     * @var array
     */
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

        if ($key === null) {
            $key = $request->getHeader('http_authentication');
        }

        if (!in_array($key, $this->apiKeys)) {
            die(
                json_encode([
                    'success' => false,
                    'error' => 'invalid_api_key'
                ])
            );
        }
    }
}
