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
            $table->string('htb_profile_url')->nullable()->after('linkedin_url');
            $table->string('htb_username')->nullable()->after('htb_profile_url');
            $table->json('htb_profile_data')->nullable()->after('htb_username');
            $table->timestamp('htb_last_synced_at')->nullable()->after('htb_profile_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'htb_profile_url',
                'htb_username',
                'htb_profile_data',
                'htb_last_synced_at',
            ]);
        });
    }
};
