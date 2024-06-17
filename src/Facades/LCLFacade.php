<?php

namespace Saasscaleup\LCL\Facades;

use Illuminate\Support\Facades\Facade;

class LCLFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'LCL';
    }
}
