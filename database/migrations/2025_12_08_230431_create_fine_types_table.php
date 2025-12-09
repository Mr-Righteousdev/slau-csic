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
        Schema::create('fine_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('default_amount', 8, 2);
            $table->text('description')->nullable();
            $table->string('auto_apply_trigger')->nullable();
            $table->integer('auto_apply_threshold')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active']);
            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fine_types');
    }
};
