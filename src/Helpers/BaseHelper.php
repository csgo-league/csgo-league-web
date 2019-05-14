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
        $this->db = new Medoo([
            'database_type' => 'mysql',
            'database_name' => env('DB_NAME'),
            'server' => env('DB_HOST'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD')
        ]);
    }
}
