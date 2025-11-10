<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Default Currency Code
    |--------------------------------------------------------------------------
    |
    | This value is used when the package needs to create a wallet for a user
    | automatically (for example, while charging a user that does not yet have
    | an associated wallet). You may override it through the
    | WALLET_DEFAULT_CURRENCY environment variable.
    |
    */
    'default_currency' => env('WALLET_DEFAULT_CURRENCY', 'IRT'),
];