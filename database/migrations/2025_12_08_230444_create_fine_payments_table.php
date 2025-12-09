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
        Schema::create('fine_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fine_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 8, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'check', 'card', 'transfer', 'other'])->default('cash');
            $table->string('receipt_number')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['fine_id']);
            $table->index(['payment_date']);
            $table->index(['payment_method']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fine_payments');
    }
};
