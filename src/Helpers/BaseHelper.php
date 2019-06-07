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
        $this->validateConfig();
        $this->db = self::getDatabaseHandler();
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

    /**
     * @throws Exception
     */
    public function validateConfig()
    {
        $requiredConfigs = [
            'DB_NAME',
            'DB_HOST',
            'DB_USERNAME',
            'DB_PASSWORD',
            'API_KEYS',
            'STEAM_API_KEY',
        ];

        foreach ($requiredConfigs as $config) {
            if (env($config) === '') {
                throw new Exception("Please set the $config in the env.php");
            }
        }
    }
}
