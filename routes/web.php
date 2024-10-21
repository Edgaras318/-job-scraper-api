<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Redis;

Route::get('/test-redis', function () {
    Redis::set('name', 'Laravel');
    return Redis::get('name'); // Should return "Laravel"
});
