<?php

namespace TechDjoin\LaravelPrometheus\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TechDjoin\LaravelPrometheus\PrometheusServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

    }

    protected function getPackageProviders($app)
    {
        return [
            PrometheusServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->config->set('app.aliases', "test");
    }
}
