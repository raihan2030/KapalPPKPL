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
        Schema::table('ultrasonic_test', function (Blueprint $table) {
            $table->decimal('t_origin', 12, 2)->after('id_inspeksi');
            $table->enum('metode_t_min', ['rule_90', 'bki'])->default('rule_90')->after('t_origin');
            $table->decimal('t_min_hitung', 12, 2)->after('metode_t_min');
            $table->enum('level_pengujian', ['A', 'B', 'C', 'D'])->default('B')->after('frekuensi_ut');
            $table->enum('kelas_area', ['A', 'B'])->default('B')->after('level_pengujian');
            $table->decimal('panjang_cacat', 12, 2)->after('kedalaman_cacat');
            $table->decimal('amplitudo_gema', 12, 2)->after('panjang_cacat');
            $table->decimal('dac_referensi', 12, 2)->after('amplitudo_gema');
            $table->enum('status_hasil', ['Pass', 'Fail'])->default('Pass')->after('grafik_ultrasonik');
            $table->text('catatan_analisis')->nullable()->after('status_hasil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ultrasonic_test', function (Blueprint $table) {
            $table->dropColumn([
                't_origin',
                'metode_t_min',
                't_min_hitung',
                'level_pengujian',
                'kelas_area',
                'panjang_cacat',
                'amplitudo_gema',
                'dac_referensi',
                'status_hasil',
                'catatan_analisis',
            ]);
        });
    }
};
