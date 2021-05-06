<?php

namespace Devzone\Ams\Facades;

use Illuminate\Support\Facades\Facade;

class Ams extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'ams';
    }
}
