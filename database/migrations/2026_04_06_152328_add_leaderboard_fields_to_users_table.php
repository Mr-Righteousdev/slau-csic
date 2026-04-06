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
            $table->integer('total_sessions_attended')->default(0)->after('attendance_count');
            $table->integer('current_streak')->default(0)->after('total_sessions_attended');
            $table->integer('longest_streak')->default(0)->after('current_streak');
            $table->integer('bonus_points')->default(0)->after('longest_streak');
            $table->decimal('score', 8, 2)->default(0)->after('bonus_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'total_sessions_attended',
                'current_streak',
                'longest_streak',
                'bonus_points',
                'score',
            ]);
        });
    }
};
