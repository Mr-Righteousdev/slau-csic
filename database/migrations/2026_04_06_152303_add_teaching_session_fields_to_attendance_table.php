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
        Schema::table('attendance', function (Blueprint $table) {
            $table->enum('status', ['present', 'late', 'absent'])->default('present')->after('check_in_method');
            $table->integer('late_threshold_minutes')->default(15)->after('status');
            $table->boolean('is_auto_absent')->default(false)->after('late_threshold_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropColumn(['status', 'late_threshold_minutes', 'is_auto_absent']);
        });
    }
};
