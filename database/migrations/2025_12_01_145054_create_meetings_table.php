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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['general', 'executive', 'special', 'training', 'workshop']);
            $table->dateTime('scheduled_at');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->string('location');
            $table->string('meeting_code')->unique(); // QR code value
            $table->integer('code_expires_minutes')->default(30); // Code validity
            $table->boolean('attendance_open')->default(false);
            $table->integer('duration_minutes')->nullable();
            $table->integer('expected_attendees')->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->text('agenda')->nullable();
            $table->text('minutes')->nullable(); // Secretary adds after meeting
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
