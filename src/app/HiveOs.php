<?php

namespace KriosMane\HiveOs\app;

/**
 * 
 */
class HiveOs {


    /**
     * 
     */
    protected $login = '';

    /**
     * 
     */
    protected $password = '';

    /**
     * 
     */
    protected $access_token = '';

    /**
     * 
     */
    protected $endpoint = 'https://api2.hiveos.farm/api/v2/';

    /**
     * 
     */
    public function __construct() {

        echo "construct".PHP_EOL;
        echo 'login: '.config('hiveosapi.login').PHP_EOL;
        echo "password".config('hiveosapi.password').PHP_EOL;
        echo "access_token".config('hiveosapi.access_token').PHP_EOL;
    }



    /**
     * 
     */
    public function test()
    {
        echo "test".PHP_EOL;
    }

}

?>