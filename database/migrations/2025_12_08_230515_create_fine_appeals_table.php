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
        Schema::create('fine_appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fine_id')->constrained()->cascadeOnDelete();
            $table->enum('appeal_reason', ['first_offense', 'special_circumstances', 'error', 'other']);
            $table->text('explanation');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->default(now());
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('decision_notes')->nullable();
            $table->timestamps();

            $table->index(['fine_id']);
            $table->index(['status']);
            $table->index(['submitted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fine_appeals');
    }
};
