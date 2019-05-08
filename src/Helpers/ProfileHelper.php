<?php

namespace B3none\League\Helpers;


class ProfileHelper extends BaseHelper
{
    /**
     * @var string
     */
    protected $steamApiKey;

    public function __construct()
    {
        parent::__construct();

        $this->steamApiKey = env('STEAM_API');
    }
}