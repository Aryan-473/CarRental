<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Option 1: Change to string column (Recommended - More flexible)
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
        });

        // Option 2: Update enum to include all roles (If you prefer enum)
        // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'vendor', 'manager', 'admin') DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Option 1: Revert to previous enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'manager', 'admin') DEFAULT 'user'");

        // Option 2: If you used the enum approach
        // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'vendor', 'manager', 'admin') DEFAULT 'user'");
    }
};
