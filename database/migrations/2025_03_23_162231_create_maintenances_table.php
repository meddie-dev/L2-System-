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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->string('maintenanceNumber')->unique();
            $table->enum('task', [
                'Annual General Inspection (LTO Required)',
                'Full Engine Diagnostics',
                'Suspension & Steering System Check',
                'Air & Fuel Filter Replacement',
                'Coolant System (Radiator Flush & Refill)',
                'Battery Check & Cleaning',
                'Transmission Fluid Check & Change',
                'Tire Rotation & Alignment',
                'Brake System Check (Pads, Fluid, etc.)',
                'Oil Change & Filter Replacement',
            ]);
            $table->decimal('amount', 10, 2);
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->enum('conditionStatus', ['good', 'fair', 'poor', 'damaged'])->default('good');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
