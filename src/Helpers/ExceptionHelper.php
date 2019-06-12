<?php

namespace B3none\League\Helpers;

use Throwable;

class ExceptionHelper
{
    /**
     * Exception handler
     *
     * @param Throwable $error
     * @return bool
     */
    public static function handle(Throwable $error): bool
    {
        header('HTTP/1.1 500 Internal Server Error');

        $response = [
            'status' => 500
        ];

        $remote = $_SERVER['REMOTE_ADDR'];
        if ($remote === '127.0.0.1' || $remote === '::1') {
            $response = array_merge($response, [
                'error' => $error->getMessage(),
                'file' => $error->getFile(),
                'line' => $error->getLine()
            ]);
        }

        die(json_encode($response));
    }
}
