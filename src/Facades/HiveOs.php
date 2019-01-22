<?php

namespace KriosMane\HiveOs\Facades;

use Illuminate\Support\Facades\Facade;

class HiveOs extends Facade
{
    /**
     * Get the registered name of the component
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hive-os';
    }
}