<?php

namespace Tiknil\BsBladeForms;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tiknil\BsBladeForms\Skeleton\SkeletonClass
 */
class BsBladeFormsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'bs-blade-forms';
    }
}
