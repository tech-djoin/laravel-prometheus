<?php

namespace TechDjoin\LaravelPrometheus;

use Prometheus\Storage\Redis;
use Prometheus\CollectorRegistry;
use Illuminate\Support\ServiceProvider;

class PrometheusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/prometheus.php', 'prometheus');
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/prometheus.php' => config_path('prometheus.php'),
            ], 'config');
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->app->bind(Adapter::class, function () {
            $connection = config('prometheus.redis_connection', 'default');

            return new Redis(
                [
                    'host'     => config("database.redis.{$connection}.host"),
                    'port'     => config("database.redis.{$connection}.port"),
                    'password' => config("database.redis.{$connection}.password"),
                    'database' => (int) config("database.redis.{$connection}.database", 0),
                ]
            );
        });

        $this->app->singleton(CollectorRegistry::class, function () {
            return new CollectorRegistry($this->app->make(Adapter::class));
        });
    }
}
