<?php

namespace TechDjoin\LaravelPrometheus\Middleware;

use Closure;
use Prometheus\Counter;
use Illuminate\Http\Request;
use Prometheus\CollectorRegistry;
use Prometheus\Histogram;

class MetricCollector
{
    protected Counter $http_request_counter;
    protected Counter $http_request_code_counter;
    protected Histogram $http_latency_histogram;

    public function __construct(CollectorRegistry $collectorRegistry)
    {
        // Skip collector construct
        if(!$this->isEnabled()) return;

        $this->http_request_counter = $collectorRegistry->getOrRegisterCounter(
            config('prometheus.namespace'), // Counter namespace
            'http_request_totals', // Counter name
            'The total number of application request http', // Counter Help string
            ['method','path','code'], // Counter labels
        );

        $this->http_latency_histogram = $collectorRegistry->getOrRegisterHistogram(
            config('prometheus.namespace'), // Counter namespace
            'http_request_latency_seconds', // Counter name
            'Latency of HTTP requests.', // Counter Help string
            ['method','path'],
            [0.1, 0.3, 0.5, 0.7, 0.9]
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
        // Record start time
        $start = microtime(true);

        $response = $next($request);

        // Skip metric collect
        if(!$this->isEnabled()) return $response;

        // Skip metric collect
        if($this->isBlackListPath($request->route()->uri)) return $response;

        // Increase the number of http requests by 1 for label uri
        $this->http_request_counter->incBy(1 , [$request->method(), $request->route()->uri,$response->getStatusCode()]);

        // Observe latency of http requests by label uri
        $latency = \microtime(true) - $start;
        $this->http_latency_histogram->observe($latency,[$request->method(), $request->route()->uri]);

        return $response;
    }

    private function isEnabled(): bool
    {
        return config('prometheus.enabled');
    }

    private function isBlackListPath($path): bool
    {
        $blackList = [
            'metrics/{secret?}'
        ];

        foreach ($blackList as $list) {
            // Pattern matching
            if(fnmatch($list, $path)) return true;
        }

        return false;
    }
}
