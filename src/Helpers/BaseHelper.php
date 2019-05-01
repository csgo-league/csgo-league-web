<?php

namespace Redline\League\Helpers;

class BaseHelper
{
    /**
     * @var \PDO
     */
    protected $PDO;

    public function __construct()
    {
        try {
            $this->PDO = new \PDO("mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_NAME'), env('DB_USERNAME'), env('DB_PASSWORD'));
        } catch (\Exception $e) {
            die('There was an errors connecting to the database.');
        }
    }
}