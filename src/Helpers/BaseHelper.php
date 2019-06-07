<?php

namespace B3none\League\Helpers;

use Medoo\Medoo;
use \Exception;

class BaseHelper
{
    /**
     * @var Medoo
     */
    protected $db;

    /**
     * BaseHelper constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->db = self::getDatabaseHandler();

        if (env('API_KEYS') === '') {
            throw new Exception('Please set the API_KEY value in the env.php');
        }
    }

    /**
     * Get the db handler
     *
     * @return Medoo
     */
    public static function getDatabaseHandler(): Medoo
    {
        return new Medoo([
            'database_type' => 'mysql',
            'database_name' => env('DB_NAME'),
            'server' => env('DB_HOST'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD')
        ]);
    }
}
