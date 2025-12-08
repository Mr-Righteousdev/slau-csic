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
        Schema::create('budget_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_category_id')->constrained('budget_categories')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('semester');
            $table->string('academic_year');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['budget_category_id', 'semester', 'academic_year'], 'budget_allocations_unique');
            $table->index(['semester', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_allocations');
    }
};
