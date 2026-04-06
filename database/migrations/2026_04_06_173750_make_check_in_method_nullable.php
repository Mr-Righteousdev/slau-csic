<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->enum('check_in_method', ['qr_code', 'manual', 'nfc', 'admin_override'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->enum('check_in_method', ['qr_code', 'manual', 'nfc', 'admin_override'])->nullable(false)->change();
        });
    }
};
