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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('id_laporan');
            
            // Foreign key ke inspeksi_ultrasonic
            $table->string('id_inspeksi', 20);
            $table->foreign('id_inspeksi')
                ->references('id_inspeksi')
                ->on('inspeksi_ultrasonic')
                ->onDelete('cascade');
            
            // File laporan PDF
            $table->string('nama_laporan', 100)
                ->comment('Nama file laporan PDF');
            $table->string('file_path', 255)
                ->comment('Path file laporan di storage');
            
            // Status laporan
            $table->enum('status_laporan', ['generated', 'downloaded', 'archived'])
                ->default('generated')
                ->comment('Status laporan: generated, downloaded, archived');
            
            // Metadata laporan
            $table->dateTime('tanggal_generate')
                ->comment('Tanggal laporan di-generate');
            $table->unsignedBigInteger('generated_by')
                ->comment('User ID pengguna yang generate laporan');
            $table->foreign('generated_by')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
            
            // Tracking akses laporan
            $table->dateTime('tanggal_download')
                ->nullable()
                ->comment('Tanggal laporan pertama kali di-download');
            $table->integer('jumlah_download')
                ->default(0)
                ->comment('Jumlah kali laporan di-download');
            
            // Catatan tambahan
            $table->text('catatan_laporan')
                ->nullable()
                ->comment('Catatan atau keterangan tambahan laporan');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
