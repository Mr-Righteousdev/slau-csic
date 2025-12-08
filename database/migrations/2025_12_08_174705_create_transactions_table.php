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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['income', 'expense']);
            $table->string('category');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->string('paid_to_from')->nullable();
            $table->enum('payment_method', ['cash', 'check', 'card', 'transfer', 'other'])->default('cash');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->boolean('requires_approval')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['date']);
            $table->index(['category']);
            $table->index(['requires_approval']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
