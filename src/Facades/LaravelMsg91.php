<?php

namespace RobinCSamuel\LaravelMsg91\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelMsg91 extends Facade
{
    /**
     * Name of the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-msg91';
    }
}
