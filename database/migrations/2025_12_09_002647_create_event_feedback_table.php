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
        Schema::create('event_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->min(1)->max(5);
            $table->integer('content_quality')->min(1)->max(5)->nullable();
            $table->integer('instructor_rating')->min(1)->max(5)->nullable();
            $table->integer('pace_rating')->min(1)->max(5)->nullable();
            $table->text('feedback_text')->nullable();
            $table->text('suggestions')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_feedback');
    }
};
