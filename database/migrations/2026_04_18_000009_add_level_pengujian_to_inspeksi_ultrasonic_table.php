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
        Schema::table('inspeksi_ultrasonic', function (Blueprint $table) {
            // Tambahkan kolom level_pengujian jika belum ada
            if (!Schema::hasColumn('inspeksi_ultrasonic', 'level_pengujian')) {
                $table->enum('level_pengujian', ['A', 'B', 'C', 'D'])
                    ->default('B')
                    ->after('frekuensi_ut')
                    ->comment('Level pengujian ISO 17640');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspeksi_ultrasonic', function (Blueprint $table) {
            if (Schema::hasColumn('inspeksi_ultrasonic', 'level_pengujian')) {
                $table->dropColumn('level_pengujian');
            }
        });
    }
};
