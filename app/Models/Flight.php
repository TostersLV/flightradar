<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'icao',
        'callsign',
        'origin_country',
        'time_position',
        'last_contact',
        'longitude',
        'latitude',
        'on_ground',
        'velocity',
        'degrees',
        'geo_altitude'
    ];
}
