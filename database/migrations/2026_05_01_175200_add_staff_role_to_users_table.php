<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('resident', 'admin', 'staff') NOT NULL DEFAULT 'resident'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('role', 'staff')->update(['role' => 'admin']);

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('resident', 'admin') NOT NULL DEFAULT 'resident'");
    }
};
