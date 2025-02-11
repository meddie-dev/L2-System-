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
            $table->string('plateNumber')->unique();
            $table->string('vehicleType') ;
            $table->string('vehicleModel');
            $table->string('vehicleMake');
            $table->string('vehicleColor');
            $table->year('vehicleYear');
            $table->integer('vehicleCapacity');
            $table->string('vehicleImage');
            $table->enum('vehicleStatus', ['available', 'unavailable','maintenance'])->default('available');
            $table->string('vehicleIssue')->nullable();
            $table->string('maintenanceDescription')->nullable();
            $table->string('maintenanceSchedule')->nullable();
            $table->enum('conditionStatus', ['good', 'fair', 'poor', 'damaged'])->default('good');

            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
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
