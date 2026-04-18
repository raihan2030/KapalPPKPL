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
            $table->enum('status_validasi', ['pending', 'validated', 'rejected'])
                ->default('pending')
                ->after('status_akseptansi')
                ->comment('Status validasi hasil analisis: pending, validated, rejected');

            $table->timestamp('validated_at')
                ->nullable()
                ->after('status_validasi')
                ->comment('Waktu validasi dilakukan');

            $table->unsignedBigInteger('validated_by')
                ->nullable()
                ->after('validated_at')
                ->comment('User ID yang melakukan validasi');

            $table->boolean('is_locked')
                ->default(false)
                ->after('validated_by')
                ->comment('Flag untuk mengunci data setelah divalidasi');

            $table->text('catatan_validasi')
                ->nullable()
                ->after('is_locked')
                ->comment('Catatan atau alasan validasi/rejection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspeksi_ultrasonic', function (Blueprint $table) {
            $table->dropColumn([
                'status_validasi',
                'validated_at',
                'validated_by',
                'is_locked',
                'catatan_validasi',
            ]);
        });
    }
};
