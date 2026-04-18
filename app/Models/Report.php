<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_inspeksi',
        'nama_laporan',
        'file_path',
        'status_laporan',
        'tanggal_generate',
        'generated_by',
        'tanggal_download',
        'jumlah_download',
        'catatan_laporan',
    ];

    protected $casts = [
        'tanggal_generate' => 'datetime',
        'tanggal_download' => 'datetime',
    ];

    /**
     * Relationship: Laporan milik InspeksiUltrasonic
     */
    public function inspeksiUltrasonic(): BelongsTo
    {
        return $this->belongsTo(InspeksiUltrasonic::class, 'id_inspeksi', 'id_inspeksi');
    }

    /**
     * Relationship: Laporan dibuat oleh User
     */
    public function userGenerator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by', 'id');
    }

    /**
     * Scope: Ambil laporan yang sudah di-download
     */
    public function scopeDownloaded($query)
    {
        return $query->where('status_laporan', 'downloaded');
    }

    /**
     * Scope: Ambil laporan berdasarkan id_inspeksi
     */
    public function scopeByInspection($query, $idInspeksi)
    {
        return $query->where('id_inspeksi', $idInspeksi);
    }

    /**
     * Method: Increment jumlah download
     */
    public function incrementDownloadCount()
    {
        $this->increment('jumlah_download');
        
        // Set tanggal_download jika belum ada
        if (is_null($this->tanggal_download)) {
            $this->update(['tanggal_download' => now()]);
        }
        
        // Update status menjadi 'downloaded'
        if ($this->status_laporan === 'generated') {
            $this->update(['status_laporan' => 'downloaded']);
        }
    }
}
