<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds request_subtype and widens document_type so new categories do not require enum migrations.
     */
    public function up(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            $table->string('request_subtype', 120)->nullable()->after('document_type');
        });

        Schema::table('document_requests', function (Blueprint $table) {
            $table->string('document_type', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            $table->dropColumn('request_subtype');
        });

        // Intentionally not reverting document_type to enum: rows may use flexible category keys.
    }
};
