<?php

namespace App\Services;

use App\Models\InspeksiUltrasonic;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReportGeneratorService
{
    /**
     * Generate PDF report untuk Ultrasonic Testing
     * 
     * @param string $idInspeksi
     * @param int $userId
     * @return Report
     */
    public function generate($idInspeksi, $userId)
    {
        // Ambil data inspeksi lengkap
        $inspeksi = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)
            ->firstOrFail();

        // Validasi: Data harus sudah divalidasi
        if ($inspeksi->status_validasi !== 'validated') {
            throw new \Exception('Data inspeksi belum divalidasi');
        }

        // Prepare data untuk template
        $data = [
            'inspeksi' => $inspeksi,
            'tanggal_generate' => now()->format('d/m/Y H:i:s'),
            'id_inspeksi' => $idInspeksi,
        ];

        // Generate PDF dari template blade
        $pdf = Pdf::loadView('reports.report_template', $data);

        // Set nama file
        $namaFile = 'Laporan_UT_' . $idInspeksi . '_' . now()->format('YmdHis') . '.pdf';
        $filePath = 'reports/' . $namaFile;

        // Simpan ke storage
        Storage::disk('public')->put(
            $filePath,
            $pdf->output()
        );

        // Simpan record ke database
        $report = Report::create([
            'id_inspeksi' => $idInspeksi,
            'nama_laporan' => $namaFile,
            'file_path' => $filePath,
            'status_laporan' => 'generated',
            'tanggal_generate' => now(),
            'generated_by' => $userId,
            'catatan_laporan' => 'Laporan otomatis di-generate oleh sistem',
        ]);

        return $report;
    }
}
