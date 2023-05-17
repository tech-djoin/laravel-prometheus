<?php

use Prometheus\Counter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prometheus\CollectorRegistry;
use Illuminate\Support\Facades\Config;
use TechDjoin\LaravelPrometheus\Middleware\MetricCollector;

it('does not initialize the counter when config is false', function () {

    // Mock the CollectorRegistry class
    $collectorRegistryMock = (object) Mockery::mock(CollectorRegistry::class);

    // Set isEnable to false
    Config::set('prometheus.enabled', false);
    new MetricCollector($collectorRegistryMock);

    // Assert that the getOrRegisterCounter method is not called
    $collectorRegistryMock
    ->shouldReceive('getOrRegisterCounter')
    ->never();
});

it('does not scrap any metric on middleware when config is false', function () {

    // Mock the Request, Closure, and Counter classes
    $requestMock = (object) Mockery::mock(Request::class);
    $counterMock = (object) Mockery::mock(Counter::class);

    // Set isEnable to false
    Config::set('prometheus.enabled', false);

    // Set up expectations and assertions
    $requestMock
        ->shouldReceive('route->uri')
        ->andReturn('/example-route');

    $closure = function () {
        return new Response();
    };

    $middleware = app()->make(MetricCollector::class);

    // Call the handle method
    $middleware->handle($requestMock, $closure);

    // Assert that the getOrRegisterCounter method is not called
    $counterMock
    ->shouldReceive('incBy')
    ->never();
});
