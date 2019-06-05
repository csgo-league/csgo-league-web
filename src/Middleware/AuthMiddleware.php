<?php

namespace B3none\League\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        $request->getHeader('authentication');

        // Authenticate user, will be available using request()->user
        $request->user = User::authenticate();

        // If authentication failed, redirect request to user-login page.
        if ($request->user === null) {
            $request->setRewriteUrl(url('user.login'));
        }
    }
}