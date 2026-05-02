<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('resident', 'admin', 'staff') NOT NULL DEFAULT 'resident'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE `users` SET `role` = 'admin' WHERE `role` = 'staff'");
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('resident', 'admin') NOT NULL DEFAULT 'resident'");
    }
};
