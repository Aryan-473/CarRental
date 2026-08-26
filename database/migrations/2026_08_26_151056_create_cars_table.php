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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('car_categories')->onDelete('restrict');
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('color');
            $table->integer('seats');
            $table->enum('transmission', ['automatic', 'manual', 'cvt']);
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid']);
            $table->decimal('price_per_day', 10, 2);
            $table->decimal('security_deposit', 10, 2)->default(0);
            $table->text('description');
            $table->json('images')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->string('license_plate')->unique();
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('mileage')->nullable();
            $table->string('insurance_policy')->nullable();
            $table->timestamp('last_maintenance')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['vendor_id', 'is_available']);
            $table->index(['category_id', 'is_approved']);
            $table->index('location');
            $table->index('license_plate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
