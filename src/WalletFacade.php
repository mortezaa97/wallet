<?php

namespace Mortezaa97\Wallet;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mortezaa97\Wallet\Skeleton\SkeletonClass
 */
class WalletFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wallet';
    }
}
