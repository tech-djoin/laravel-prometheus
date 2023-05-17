<?php

namespace TechDjoin\LaravelPrometheus\Middleware;

use Closure;
use Prometheus\Counter;
use Illuminate\Http\Request;
use Prometheus\CollectorRegistry;

class MetricCollector
{
    protected Counter $http_request_counter;

    public function __construct(CollectorRegistry $collectorRegistry)
    {
        // Skip collector construct
        if(!$this->isEnabled()) return;

        $this->http_request_counter = $collectorRegistry->getOrRegisterCounter(
            config('prometheus.namespace', 'app'), // Counter namespace
            'http_request_totals', // Counter name
            'HTTP request Total', // Counter Help string
            ['path'], // Counter labels
        );
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip metric collect
        if(!$this->isEnabled()) return $next($request);

        $response = $next($request);

        // Increase the number of http requests by 1 for label uri
        $this->http_request_counter->incBy(1 , [$request->route()->uri]);

        return $response;
    }

    private function isEnabled(): bool
    {
        return config('prometheus.enabled');
    }
}
