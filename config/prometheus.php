<?php

return [
    /**
     * Boolean value for enable and disable the Laravel Prometheus middleware.
     */
    'enabled' => env('LARAVEL_PROMETHEUS_ENABLED', true),

    /**
     * Secret value for access the /metrics endpoint
     */
    'secret' => env('LARAVEL_PROMETHEUS_SECRET', null),
];
