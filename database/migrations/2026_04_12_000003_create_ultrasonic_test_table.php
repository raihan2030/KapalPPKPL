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
        Schema::create('ultrasonic_test', function (Blueprint $table) {
            $table->id('id_ultrasonic');
            $table->unsignedBigInteger('id_inspeksi');
            $table->index('id_inspeksi');
            $table->decimal('nilai_ketebalan', 12, 2);
            $table->decimal('batas_standar', 12, 2);
            $table->decimal('frekuensi_ut', 12, 2);
            $table->string('jenis_cacat', 255);
            $table->decimal('kedalaman_cacat', 12, 2);
            $table->string('grafik_ultrasonik');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ultrasonic_test');
    }
};
