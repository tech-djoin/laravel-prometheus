<?php

namespace TechDjoin\LaravelPrometheus\Tests\Unit;

it('check the pest is work', function () {
    dd(env('REDIS_HOST'), config('REDIS_PORT'));

    expect(true)->toBeTrue();
});
