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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('plateNumber')->unique();
            $table->string('vehicleType') ;
            $table->string('vehicleModel');
            $table->string('vehicleMake');
            $table->string('vehicleColor');
            $table->year('vehicleYear');
            $table->enum('vehicleFuelType', ['diesel', 'gasoline', 'electric'])->default('diesel');
            $table->integer('vehicleCapacity');
            $table->decimal('vehicleCost', 8, 2);
            $table->integer('vehicleLifespan');
            $table->string('vehicleImage');
            $table->enum('vehicleStatus', ['available', 'unavailable','maintenance'])->default('available');
            $table->decimal('fuel_efficiency', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
