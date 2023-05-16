<?php

use Prometheus\RenderTextFormat;
use Prometheus\CollectorRegistry;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/metrics', function (CollectorRegistry $collectorRegistry) {
    $renderer = new RenderTextFormat();
    $result = $renderer->render($collectorRegistry->getMetricFamilySamples());

    return response($result, 200)
        ->header('Content-Type', RenderTextFormat::MIME_TYPE);
});
