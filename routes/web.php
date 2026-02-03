<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

Route::get('/', [FlightController::class, 'index']);

Route::get('/api/proxy-flights', function () {
    return Cache::remember('flight_data', 30, function () {
        $response = Http::get('https://opensky-network.org/api/states/all');
        return $response->json();
    });
});