<?php

return [

    /**
     * Namespace for Laravel Prometheus metrics.
     */
    'namespace' => env('LARAVEL_PROMETHEUS_NAMESPACE', 'app'),

    /**
     * Boolean value for enable and disable the Laravel Prometheus middleware.
     */
    'enabled' => env('LARAVEL_PROMETHEUS_ENABLED', true),

    /**
     * Secret value for access the /metrics endpoint
     */
    'secret' => env('LARAVEL_PROMETHEUS_SECRET', null),

    /**
     * Redis connection name to use for storing metrics.
     * Corresponds to a key under config('database.redis').
     * Defaults to 'default' (db0) when null.
     */
    'redis_connection' => env('LARAVEL_PROMETHEUS_REDIS_CONNECTION', 'default'),
];
