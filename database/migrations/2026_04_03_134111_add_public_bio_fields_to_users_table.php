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
            $table->string('faculty')->nullable()->after('program');
            $table->date('date_of_birth')->nullable()->after('year_of_study');
            $table->string('gender', 30)->nullable()->after('date_of_birth');
            $table->string('residence')->nullable()->after('gender');
            $table->string('headline')->nullable()->after('bio');
            $table->string('specialization_track')->nullable()->after('headline');
            $table->text('notable_problems_solved')->nullable()->after('specialization_track');
            $table->text('achievements_summary')->nullable()->after('notable_problems_solved');
            $table->string('competition_rank')->nullable()->after('achievements_summary');
            $table->string('emergency_contact_name')->nullable()->after('competition_rank');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'faculty',
                'date_of_birth',
                'gender',
                'residence',
                'headline',
                'specialization_track',
                'notable_problems_solved',
                'achievements_summary',
                'competition_rank',
                'emergency_contact_name',
                'emergency_contact_phone',
            ]);
        });
    }
};
