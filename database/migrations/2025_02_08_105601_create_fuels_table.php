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
        Schema::create('fuels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_ticket_id')->references('id')->on('trip_tickets')->onDelete('cascade');
            $table->foreignId('fleet_card_id')->references('id')->on('fleet_cards')->onDelete('cascade');
            $table->string('fuelNumber')->unique();
            $table->enum('fuelType', ['diesel', 'gasoline', 'electric'])->default('diesel');
            $table->decimal('estimatedFuelConsumption', 8, 2); 
            $table->decimal('estimatedCost', 10, 2); 
            $table->timestamp('fuelDate');
            $table->enum('fuelStatus', ['scheduled', 'completed'])->default('scheduled');
            $table->timestamps();

            $table->foreignId('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuels');
    }
};
