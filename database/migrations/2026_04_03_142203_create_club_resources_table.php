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
        Schema::create('club_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('platform')->nullable();
            $table->string('difficulty')->nullable();
            $table->string('status')->default('open');
            $table->string('location')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('external_url')->nullable();
            $table->text('summary');
            $table->text('details')->nullable();
            $table->unsignedInteger('target_total')->default(1);
            $table->unsignedInteger('points')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_resources');
    }
};
