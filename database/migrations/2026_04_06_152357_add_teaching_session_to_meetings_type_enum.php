<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL doesn't support ALTER TABLE for enum, so we need raw SQL
        // First check if column exists
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            // Add the new enum value using MODIFY
            DB::statement("ALTER TABLE meetings MODIFY COLUMN type ENUM('general', 'executive', 'special', 'training', 'workshop', 'teaching_session')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE meetings MODIFY COLUMN type ENUM('general', 'executive', 'special', 'training', 'workshop')");
        }
    }
};
