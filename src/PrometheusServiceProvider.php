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
            $this->publishes([
                __DIR__.'/../config/prometheus.yml' => \base_path('prometheus.yml.bak'),
            ]);
            $this->publishes([
                __DIR__.'/../config/docker-compose.yml' => \base_path('docker-compose.yml.bak'),
            ]);
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->app->bind(Adapter::class, function () {
            return new Redis(
                [
                    'host' => config('database.redis.default.host'),
                    'port' => config('database.redis.default.port'),
                    'password' => config('database.redis.default.password'),
                    'timeout' => 1, // one seconds
                ]
            );
        });

        $this->app->singleton(CollectorRegistry::class, function () {
            return new CollectorRegistry($this->app->make(Adapter::class));
        });
    }
}
