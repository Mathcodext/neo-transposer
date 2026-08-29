<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Debugbar Settings
    |--------------------------------------------------------------------------
    |
    | Debugbar is disabled in production environments and only enabled when
    | explicitly requested via DEBUGBAR_ENABLED env var in non-production.
    |
    */

    'enabled' => env('DEBUGBAR_ENABLED', false),

];
