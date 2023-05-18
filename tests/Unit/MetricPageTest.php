<?php

use Illuminate\Support\Facades\Config;

it("can access metrics pages", function(){
    // Simulate an HTTP GET request to the /metrics route without a secret
    $response = $this->get('/metrics');

    // Assert that the response has the desired status code
    $response->assertStatus(200);
});

it("can access metrics pages with secret", function(){
    // Set config secret value
    Config::set('prometheus.secret', 'secret');

    // Simulate an HTTP GET request to the /metrics route without a secret
    $response = $this->get('/metrics/secret');

    // Assert that the response has the desired status code
    $response->assertStatus(200);
});

it("cannot access metrics pages with without secret", function(){
    // Set config secret value
    Config::set('prometheus.secret', 'secret');

    // Simulate an HTTP GET request to the /metrics route without a secret
    $response = $this->get('/metrics');

    // Assert that the response has the desired status code
    $response->assertStatus(404);
});

it("cannot access metrics pages with wrong secret", function(){
    // Set config secret value
    Config::set('prometheus.secret', 'secret');

    // Simulate an HTTP GET request to the /metrics route without a secret
    $response = $this->get('/metrics/wrong');

    // Assert that the response has the desired status code
    $response->assertStatus(404);
});
