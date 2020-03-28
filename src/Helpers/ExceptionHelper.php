<?php

namespace B3none\League\Helpers;

use Throwable;

class ExceptionHelper
{
    /**
     * Exception handler
     *
     * @param Throwable $error
     * @return string
     */
    public static function handle(Throwable $error): string
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

        return response()->httpCode(500)->json($response);
    }
}
