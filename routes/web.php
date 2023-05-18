<?php

use Illuminate\Http\Request;
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

Route::get('/metrics/{secret?}', function (Request $request, CollectorRegistry $collectorRegistry) {

    // Get secret value from request parameters
    $secretClient = $request->route('secret');

    // Get secret value from config
    $secretConfig = config('prometheus.secret');

    // Compare secret, if not equal then redirect to not found page
    if($secretClient !== $secretConfig) abort(404);

    $renderer = new RenderTextFormat();
    $result = $renderer->render($collectorRegistry->getMetricFamilySamples());

    return response($result, 200)
        ->header('Content-Type', RenderTextFormat::MIME_TYPE);
});
