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
        Schema::table('users', function (Blueprint $table) {
            // Add vendor-related fields
            $table->string('phone')->nullable()->after('email');
            $table->string('company_name')->nullable()->after('phone');
            $table->text('address')->nullable()->after('company_name');
            $table->string('avatar')->nullable()->after('address');
            $table->string('timezone')->default('UTC')->after('avatar');
            $table->timestamp('last_login_at')->nullable()->after('timezone');
            $table->string('two_factor_secret')->nullable()->after('last_login_at');
            $table->boolean('two_factor_enabled')->default(false)->after('two_factor_secret');
            
            // Indexes
            $table->index('phone');
            $table->index('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'company_name',
                'address',
                'avatar',
                'timezone',
                'last_login_at',
                'two_factor_secret',
                'two_factor_enabled'
            ]);
        });
    }
};