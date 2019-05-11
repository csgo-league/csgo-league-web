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
        try {
            response()->redirect($this->steam->loginUrl());
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }

    /**
     * Log out of steam
     */
    public function logout(): void
    {
        try {
            $this->steam->logout();
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }
}
