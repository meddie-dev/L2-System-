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
        Schema::create('trip_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreignId('vehicle_reservation_id')->references('id')->on('vehicle_reservations')->onDelete('cascade');
            $table->foreignId('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreignId('fleet_card_id')->references('id')->on('fleet_cards')->onDelete('cascade');
            $table->string('tripNumber')->unique();
            $table->string('destination')->nullable();
            $table->timestamp('departureTime')->nullable();
            $table->timestamp('arrivalTime')->nullable();
            $table->decimal('allocatedFuel', 8, 2)->nullable();
            $table->enum('status', ['scheduled', 'in_transit', 'delayed', 'delivered'])->default('scheduled');
            $table->decimal('distance', 8, 2)->nullable();
            $table->decimal('duration', 8, 2)->nullable();
            $table->decimal('pickUpLat', 10, 7)->nullable();
            $table->decimal('pickUpLng', 10, 7)->nullable();
            $table->decimal('dropOffLat', 10, 7)->nullable();
            $table->decimal('dropOffLng', 10, 7)->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->integer('delay_minutes')->nullable();
            $table->integer('rating')->nullable();
            $table->text('feedback')->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_tickets');
    }
};
