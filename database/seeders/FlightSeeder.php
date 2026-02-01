<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Flight;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $api = Http::get(env('API_URL'));

        $data = $api->json();
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        $flightsData = $data['states'];

        foreach ($flightsData as $flightData){
            Flight::create([
                'icao' => $flightData['0'] ?? '',
                'callsign' => $flightData['1'] ?? '',
                'origin_country' => $flightData['2'] ?? '',
                'time_position' => $flightData['3'],
                'last_contact' => $flightData['4'],
                'longitude' => $flightData['5'],
                'latitude' => $flightData['6'],
                'on_ground' => $flightData['8'],
                'velocity' => $flightData['9'],
                'degrees' => $flightData['11'] ?? null,
                'geo_altitude' => $flightData['13'],
            ]);
        }
    }
}
