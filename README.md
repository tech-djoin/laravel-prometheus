# laravel-prometheus
[![Latest Version on Packagist](https://img.shields.io/packagist/v/tech-djoin/laravel-prometheus.svg?style=flat-square)](https://packagist.org/packages/tech-djoin/laravel-prometheus)
[![Total Downloads](https://poser.pugx.org/tech-djoin/laravel-prometheus/downloads)](https://packagist.org/packages/tech-djoin/laravel-prometheus)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Laravel Prometheus is a package that allows you to integrate Prometheus, a popular open-source monitoring and alerting toolkit, into your Laravel applications. Prometheus is widely used for monitoring various aspects of software systems, including metrics, time series data, and alerting.

This package uses Redis to do the client side aggregation and also need installed phpredis extension for communication with the Redis key-value store. To install the phpredis extension you can follow link below:

```bash
https://github.com/phpredis/phpredis/blob/develop/INSTALL.md
```


## Installation

You can install the package via composer:

```bash
composer require tech-djoin/laravel-prometheus
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="TechDjoin\LaravelPrometheus\PrometheusServiceProvider" --tag="config" 
```
## Usage

This packages provides a middleware which can be added as a global middleware or as a single route.

```php
// in `app/Http/Kernel.php`

protected $middleware = [
    // ...
    
    \TechDjoin\LaravelPrometheus\Middleware\MetricCollector::class
];
```

```php
// in a routes file

Route::post('/dashboard', function () {
    //
})->middleware(\TechDjoin\LaravelPrometheus\Middleware\MetricCollector::class);
```

## Exporting Metrics
This package adds a `/metrics` end-point, enabled by default, which exposes all metrics gathered by collectors.

If you would like to protect this end-point, you can add secret value on `LARAVEL_PROMETHEUS_SECRET` in your .env files, and you can only access the end-point using `/metrics/{secret}`

## Testing

```bash
composer test
```

## License
This project is licensed under the MIT License - see the [LICENSE.md](https://github.com/MarketingPipeline/README-Quotes/blob/main/LICENSE) file for details.

## Contributors
<a href="https://github.com/tech-djoin/laravel-prometheus/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=tech-djoin/laravel-prometheus" />
</a>
