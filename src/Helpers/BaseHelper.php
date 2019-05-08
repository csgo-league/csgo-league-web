<?php

namespace B3none\League\Helpers;

use Medoo\Medoo;

class BaseHelper
{
    /**
     * @var Medoo
     */
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new Medoo([
                'database_type' => 'mysql',
                'database_name' => env('DB_NAME'),
                'server' => env('DB_HOST'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD')
            ]);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }
}
