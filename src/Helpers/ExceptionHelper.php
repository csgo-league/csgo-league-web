<?php

namespace B3none\League\Helpers;

class ExceptionHelper
{
    /**
     * Exception handler
     *
     * @param \Throwable $error
     * @param bool $shouldDie
     * @return bool
     */
    public static function handle(\Throwable $error, bool $shouldDie = true): bool
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

        echo json_encode($response);

        if (!$shouldDie) {
            return true;
        }

        die;
    }
}