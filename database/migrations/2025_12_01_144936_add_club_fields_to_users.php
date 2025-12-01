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
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('program')->nullable(); // Degree program
            $table->integer('year_of_study')->nullable();
            $table->enum('membership_type', ['active', 'associate'])->default('active');
            $table->enum('membership_status', ['pending', 'active', 'suspended', 'revoked'])->default('pending');
            $table->boolean('is_discord_member')->default(false);
            $table->string('discord_username')->nullable();
            $table->date('joined_at')->nullable();
            $table->text('bio')->nullable();
            $table->string('github_username')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('profile_photo')->nullable();
            $table->integer('attendance_count')->default(0); // Cached count
            $table->integer('events_attended')->default(0); // Cached count
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
