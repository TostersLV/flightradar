<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('icao') ;
            $table->string('callsign');
            $table->string('origin_country');
            $table->integer('time_position')->nullable();
            $table->integer('last_contact');
            $table->float('longitude')->nullable();
            $table->float('latitude')->nullable();
            $table->boolean('on_ground');
            $table->float('velocity')->nullable();
            $table->float('degrees')->nullable(); 
            $table->float('geo_altitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
