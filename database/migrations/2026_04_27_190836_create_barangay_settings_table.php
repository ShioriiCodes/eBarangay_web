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
        Schema::create('barangay_settings', function (Blueprint $table) {
            $table->id();
            $table->string('barangay_name')->default('Alfonso XIII');
            $table->string('municipality')->default('Quezon');
            $table->string('province')->default('Palawan');
            // Official names are consumed when rendering printable templates.
            $table->string('captain_name')->nullable();
            $table->string('secretary_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->text('office_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_settings');
    }
};
