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
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fine_type_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 8, 2);
            $table->text('reason');
            $table->date('issue_date');
            $table->date('due_date');
            $table->enum('status', ['pending', 'paid', 'partially_paid', 'waived', 'overdue'])->default('pending');
            $table->decimal('amount_paid', 8, 2)->default(0);
            $table->decimal('balance', 8, 2)->storedAs('amount - amount_paid');
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('waived_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('waived_reason')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['fine_type_id']);
            $table->index(['status']);
            $table->index(['due_date']);
            $table->index(['issue_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
