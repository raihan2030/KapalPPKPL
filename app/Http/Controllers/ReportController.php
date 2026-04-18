<?php

namespace App\Http\Controllers;

use App\Models\InspeksiUltrasonic;
use App\Models\Report;
use App\Services\ReportGeneratorService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $reportGeneratorService;

    public function __construct(ReportGeneratorService $reportGeneratorService)
    {
        $this->reportGeneratorService = $reportGeneratorService;
    }

    /**
     * Generate laporan PDF untuk Ultrasonic Testing
     * 
     * @param string $idInspeksi
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function generate($idInspeksi)
    {
        try {
            // Validasi: Cari data inspeksi
            $inspeksi = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)
                ->first();

            if (!$inspeksi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data inspeksi tidak ditemukan. ID: ' . $idInspeksi,
                ], 404);
            }

            // Validasi: Cek apakah sudah tervalidasi
            if ($inspeksi->status_validasi !== 'validated') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hasil belum divalidasi. Status validasi harus "validated"',
                    'current_status' => $inspeksi->status_validasi,
                ], 403);
            }

            // Cek apakah laporan sudah ada
            $existingReport = Report::byInspection($idInspeksi)->first();
            if ($existingReport) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan sudah pernah di-generate sebelumnya',
                    'report_id' => $existingReport->id_laporan,
                    'laporan' => $existingReport,
                ], 200);
            }

            // Generate laporan menggunakan service
            // Gunakan default user ID (1) jika tidak ada user login
            $userId = Auth::check() ? Auth::id() : 1;
            $report = $this->reportGeneratorService->generate($idInspeksi, $userId);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil di-generate',
                'report_id' => $report->id_laporan,
                'laporan' => $report,
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error generating report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat generate laporan: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getTraceAsString() : 'Server error',
            ], 500);
        }
    }

    /**
     * Download laporan PDF
     * 
     * @param int $idLaporan
     * @return \Illuminate\Http\Response
     */
    public function download($idLaporan)
    {
        try {
            // Cari laporan
            $report = Report::findOrFail($idLaporan);

            // Validasi: File harus ada
            if (!Storage::disk('public')->exists($report->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File laporan tidak ditemukan di server',
                ], 404);
            }

            // Increment counter download
            $report->incrementDownloadCount();

            // Download file
            return response()->download(
                storage_path('app/public/' . $report->file_path),
                $report->nama_laporan,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $report->nama_laporan . '"',
                ]
            );

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat download laporan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Preview laporan (display di browser)
     * 
     * @param int $idLaporan
     * @return \Illuminate\Http\Response
     */
    public function preview($idLaporan)
    {
        try {
            // Cari laporan
            $report = Report::findOrFail($idLaporan);

            // Validasi: File harus ada
            if (!Storage::disk('public')->exists($report->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File laporan tidak ditemukan di server',
                ], 404);
            }

            // Increment counter download
            $report->incrementDownloadCount();

            // Preview file (inline di browser)
            return response()->file(
                storage_path('app/public/' . $report->file_path),
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $report->nama_laporan . '"',
                ]
            );

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat preview laporan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List laporan untuk inspeksi tertentu
     * 
     * @param string $idInspeksi
     * @return \Illuminate\Http\JsonResponse
     */
    public function listByInspection($idInspeksi)
    {
        try {
            $reports = Report::byInspection($idInspeksi)
                ->orderByDesc('tanggal_generate')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $reports,
                'count' => count($reports),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
