<?php

namespace B3none\League\Controllers;

class LoginController extends BaseController
{
    /**
     * Log in to steam
     *
     * @return void
     */
    public function login(): void
    {
        response()->redirect($this->steam->loginUrl());
    }

    /**
     * Log out of steam
     */
    public function logout(): void
    {
        $this->steam->logout();
    }
}
