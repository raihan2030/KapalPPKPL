<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UltrasonicTest extends Model
{
    use HasFactory;

    protected $table = 'ultrasonic_test';

    protected $primaryKey = 'id_ultrasonic';

    protected $fillable = [
        'id_inspeksi',
        't_origin',
        'metode_t_min',
        't_min_hitung',
        'nilai_ketebalan',
        'batas_standar',
        'frekuensi_ut',
        'level_pengujian',
        'kelas_area',
        'jenis_cacat',
        'kedalaman_cacat',
        'panjang_cacat',
        'amplitudo_gema',
        'dac_referensi',
        'grafik_ultrasonik',
        'status_hasil',
        'catatan_analisis',
    ];

    protected $casts = [
        't_origin' => 'decimal:2',
        't_min_hitung' => 'decimal:2',
        'nilai_ketebalan' => 'decimal:2',
        'batas_standar' => 'decimal:2',
        'frekuensi_ut' => 'decimal:2',
        'kedalaman_cacat' => 'decimal:2',
        'panjang_cacat' => 'decimal:2',
        'amplitudo_gema' => 'decimal:2',
        'dac_referensi' => 'decimal:2',
    ];
}
