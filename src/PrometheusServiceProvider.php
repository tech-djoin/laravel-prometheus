<?php

namespace TechDjoin\LaravelPrometheus;

use Illuminate\Support\Facades\Log;
use Prometheus\Storage\Redis;
use Prometheus\CollectorRegistry;
use Illuminate\Support\ServiceProvider;
use Prometheus\Storage\InMemory;

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

        $this->app->bind(Adapter::class, function ($app) {
            return new Redis(
                [
                    'host' => config('database.redis.default.host'),
                    'port' => config('database.redis.default.port'),
                    'password' => config('database.redis.default.password'),
                    'timeout' => 1, // one second
                ]
            );
        });

        $this->app->singleton(CollectorRegistry::class, function () {
            try {
                return new CollectorRegistry($this->app->make(Adapter::class));
            } catch (\Throwable $th) {
                Log::error($th);

                // In case of exception during initialization Redis adapter
                // Create a adapter In Memory
                return new CollectorRegistry(new InMemory());
            }
        });
    }
}
