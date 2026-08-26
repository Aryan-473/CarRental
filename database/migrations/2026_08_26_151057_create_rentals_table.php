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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');
            $table->dateTime('pickup_date');
            $table->dateTime('return_date');
            $table->dateTime('actual_pickup_date')->nullable();
            $table->dateTime('actual_return_date')->nullable();
            $table->string('pickup_location');
            $table->string('return_location');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('security_deposit', 10, 2)->default(0);
            $table->decimal('security_deposit_refunded', 10, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->json('extras')->nullable();
            $table->json('damage_report')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['car_id', 'status']);
            $table->index(['pickup_date', 'return_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
