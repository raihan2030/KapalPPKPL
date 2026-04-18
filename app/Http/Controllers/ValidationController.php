<?php

namespace App\Http\Controllers;

use App\Models\InspeksiUltrasonic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidationController extends Controller
{
    /**
     * Menampilkan halaman validasi hasil analisis
     */
    public function showValidationPage($idInspeksi)
    {
        $inspeksi = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)->firstOrFail();

        // Jika sudah divalidasi dan terkunci, tampilkan pesan
        if ($inspeksi->is_locked) {
            return redirect()
                ->route('ultrasonic.analysis.result', ['id' => $idInspeksi])
                ->with('info', 'Data sudah divalidasi dan terkunci.');
        }

        return view('ultrasonic.validate', compact('inspeksi'));
    }

    /**
     * Memvalidasi hasil analisis
     */
    public function validateAnalysis(Request $request, $idInspeksi)
    {
        $request->validate([
            'catatan_validasi' => 'nullable|string|max:1000',
        ]);

        $inspeksi = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)->firstOrFail();

        // Cegah jika sudah divalidasi
        if ($inspeksi->is_locked) {
            return redirect()
                ->back()
                ->with('error', 'Data sudah divalidasi dan terkunci, tidak dapat diubah.');
        }

        // Update status validasi
        $inspeksi->update([
            'status_validasi' => 'validated',
            'validated_at' => now(),
            'validated_by' => Auth::id(),
            'is_locked' => true,
            'catatan_validasi' => $request->input('catatan_validasi'),
        ]);

        return redirect()
            ->route('ultrasonic.analysis.result', ['id' => $idInspeksi])
            ->with('success', 'Hasil analisis berhasil divalidasi dan data terkunci.');
    }

    /**
     * Menolak hasil analisis
     */
    public function rejectAnalysis(Request $request, $idInspeksi)
    {
        $request->validate([
            'catatan_validasi' => 'required|string|max:1000',
        ]);

        $inspeksi = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)->firstOrFail();

        // Cegah jika sudah divalidasi
        if ($inspeksi->is_locked) {
            return redirect()
                ->back()
                ->with('error', 'Data sudah divalidasi dan terkunci, tidak dapat diubah.');
        }

        // Update status rejection
        $inspeksi->update([
            'status_validasi' => 'rejected',
            'validated_at' => now(),
            'validated_by' => Auth::id(),
            'catatan_validasi' => $request->input('catatan_validasi'),
        ]);

        return redirect()
            ->route('ultrasonic.analysis.result', ['id' => $idInspeksi])
            ->with('warning', 'Hasil analisis ditolak. Silahkan lakukan pengukuran ulang.');
    }

    /**
     * Menampilkan halaman laporan (dengan pengecekan validasi)
     */
    public function generateReport($idInspeksi)
    {
        $inspeksi = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)->firstOrFail();

        // AC2: Jika belum divalidasi, tolak akses ke laporan
        if ($inspeksi->status_validasi !== 'validated' || !$inspeksi->is_locked) {
            return redirect()
                ->route('ultrasonic.analysis.result', ['id' => $idInspeksi])
                ->with('error', 'Hasil analisis harus divalidasi terlebih dahulu sebelum membuat laporan.');
        }

        // Lanjutkan ke halaman laporan atau generate laporan
        return view('ultrasonic.report', compact('inspeksi'));
    }
}
