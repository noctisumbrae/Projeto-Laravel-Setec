<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('qr_token', 64)->nullable()->unique()->after('id');
            $table->timestamp('attendance_window_start')->nullable();
            $table->timestamp('attendance_window_end')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropUnique(['qr_token']);
            $table->dropColumn(['qr_token', 'attendance_window_start', 'attendance_window_end']);
        });
    }
};