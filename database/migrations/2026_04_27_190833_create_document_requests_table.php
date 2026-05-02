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
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('document_type', [
                'barangay_clearance',
                'certificate_of_indigency',
                'certificate_of_residency',
                'barangay_id',
            ]);
            $table->text('purpose');
            // Stores structured template input for future document auto-fill generation.
            $table->json('request_data')->nullable();
            $table->enum('status', [
                'pending',
                'under_review',
                'approved',
                'ready_for_printing',
                'ready_for_claiming',
                'completed',
                'rejected',
            ])->default('pending');
            $table->text('remarks')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            // Stores rendered document path for preview/download/print in future PDF workflow.
            $table->string('printable_file_path')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('document_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
