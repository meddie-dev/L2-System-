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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreignId('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->string('trackingNumber', 50)->unique();
            $table->enum('shipmentStatus', ['scheduled', 'in_transit', 'delayed', 'delivered']);
            $table->timestamp('departureTime')->nullable();
            $table->timestamp('arrivalTime')->nullable();
            $table->string('currentLocation', 255)->nullable();
            $table->timestamp('estimatedDeliveryTime')->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
