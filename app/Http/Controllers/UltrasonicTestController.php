<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUltrasonicTestRequest;
use App\Services\UltrasonicTestService;
use App\Services\UltrasonicAnalysisService;
use App\Models\InspeksiUltrasonic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class UltrasonicTestController extends Controller
{
    public function __construct(
        private readonly UltrasonicTestService $ultrasonicTestService,
        private readonly UltrasonicAnalysisService $analysisService
    ) {}

    /**
     * Show create form for ultrasonic test
     */
    public function create(int $idInspeksi): View|RedirectResponse
    {
        $shipType = request('shipType', 'unknown');
        $shipArea = request('shipArea', 'unknown');

        $shipTypeLabels = [
            'tanker' => 'Tanker',
            'bulk_carrier' => 'Bulk Carrier',
            'container_ship' => 'Container Ship',
            'general_cargo' => 'General Cargo',
            'unknown' => 'Tidak Diketahui',
        ];

        // AC3: Cek apakah data sudah terkunci (sudah divalidasi)
        $existingInspeksi = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)->first();
        if ($existingInspeksi && $existingInspeksi->is_locked) {
            return redirect()
                ->route('ultrasonic.analysis.result', ['id' => $idInspeksi])
                ->with('error', 'Data sudah divalidasi dan terkunci. Tidak dapat diedit lagi.');
        }

        return view('ultrasonic.create', [
            'idInspeksi' => $idInspeksi,
            'shipType' => $shipTypeLabels[$shipType] ?? $shipTypeLabels['unknown'],
            'shipArea' => $shipArea !== 'unknown' ? $shipArea : 'Tidak Diketahui',
        ]);
    }

    /**
     * Store ultrasonic test data and redirect to analysis result
     */
    public function store(StoreUltrasonicTestRequest $request, int $idInspeksi): RedirectResponse
    {
        try {
            // AC3: Cek apakah data sudah terkunci (sudah divalidasi)
            $existingInspeksi = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)->first();
            if ($existingInspeksi && $existingInspeksi->is_locked) {
                return redirect()
                    ->route('ultrasonic.analysis.result', ['id' => $idInspeksi])
                    ->with('error', 'Data sudah divalidasi dan terkunci. Tidak dapat diubah lagi.');
            }

            // Simpan/Update data inspeksi ke tabel inspeksi_ultrasonic (satu-satunya sumber data)
            if (!$existingInspeksi) {
                // Buat record baru
                InspeksiUltrasonic::create([
                    'id_inspeksi' => $idInspeksi,
                    'jenis_kapal' => $request->ship_type ?? 'Tanker',
                    'area_kapal' => $request->ship_area ?? 'Lambung',
                    't_origin' => $request->t_origin,
                    'nilai_ketebalan' => $request->nilai_ketebalan,
                    'batas_standar' => $request->batas_standar,
                    'metode_perhitungan' => $request->metode_t_min,
                    'frekuensi_ut' => $request->frekuensi_ut,
                    'level_pengujian' => $request->level_pengujian,
                    'kelas_area' => $request->kelas_area,
                    'jenis_cacat' => $request->jenis_cacat,
                    'kedalaman_cacat' => $request->kedalaman_cacat ?? 0,
                    'panjang_cacat' => $request->panjang_cacat ?? 0,
                    'echo_amplitude' => $request->amplitudo_gema,
                    'persentase_penipisan' => 0,
                    'status_ketebalan' => 'OK',
                ]);
            } else {
                // Update record yang sudah ada
                $existingInspeksi->update([
                    't_origin' => $request->t_origin,
                    'nilai_ketebalan' => $request->nilai_ketebalan,
                    'batas_standar' => $request->batas_standar,
                    'metode_perhitungan' => $request->metode_t_min,
                    'frekuensi_ut' => $request->frekuensi_ut,
                    'level_pengujian' => $request->level_pengujian,
                    'kelas_area' => $request->kelas_area,
                    'jenis_cacat' => $request->jenis_cacat,
                    'kedalaman_cacat' => $request->kedalaman_cacat ?? 0,
                    'panjang_cacat' => $request->panjang_cacat ?? 0,
                    'echo_amplitude' => $request->amplitudo_gema,
                ]);
            }

            // REDIRECT LANGSUNG KE HALAMAN HASIL
            return redirect("/ultrasonic-analysis/result/{$idInspeksi}")
                ->with('success', 'Data berhasil disimpan!');
        } catch (Throwable $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error: ' . $exception->getMessage());
        }
    }

    /**
     * Store ultrasonic test data (API response - JSON)
     */
    public function storeApi(StoreUltrasonicTestRequest $request, int $idInspeksi): JsonResponse
    {
        try {
            $ultrasonicTest = $this->ultrasonicTestService->store($idInspeksi, $request->validated());

            return response()->json([
                'status' => 'success',
                'data' => $ultrasonicTest,
                'message' => 'Data ultrasonic test berhasil disimpan.',
            ], 201);
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Gagal menyimpan data ultrasonic test.',
            ], 500);
        }
    }

    /**
     * Show edit form for ultrasonic test
     */
    public function edit(string $idInspeksi)
    {
        $inspeksi = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)->firstOrFail();

        // AC3: Cek apakah data sudah terkunci (sudah divalidasi)
        if ($inspeksi->is_locked) {
            return redirect()
                ->route('ultrasonic.analysis.result', ['id' => $idInspeksi])
                ->with('error', 'Data sudah divalidasi dan terkunci. Tidak dapat diedit lagi.');
        }

        return view('ultrasonic.edit', [
            'inspeksi' => $inspeksi,
            'isEdit' => true,
        ]);
    }

    /**
     * Update ultrasonic test data in database
     */
    public function update(StoreUltrasonicTestRequest $request, string $idInspeksi): RedirectResponse
    {
        try {
            $inspeksi = InspeksiUltrasonic::where('id_inspeksi', $idInspeksi)->firstOrFail();

            // AC3: Cek apakah data sudah terkunci (sudah divalidasi)
            if ($inspeksi->is_locked) {
                return redirect()
                    ->route('ultrasonic.analysis.result', ['id' => $idInspeksi])
                    ->with('error', 'Data sudah divalidasi dan terkunci. Tidak dapat diubah lagi.');
            }

            // Update data inspeksi
            $inspeksi->update([
                't_origin' => $request->t_origin,
                'metode_perhitungan' => $request->metode_t_min,
                'nilai_ketebalan' => $request->nilai_ketebalan,
                'batas_standar' => $request->batas_standar,
                'frekuensi_ut' => $request->frekuensi_ut,
                'level_pengujian' => $request->level_pengujian,
                'kelas_area' => $request->kelas_area,
                'jenis_cacat' => $request->jenis_cacat,
                'kedalaman_cacat' => $request->kedalaman_cacat ?? 0,
                'panjang_cacat' => $request->panjang_cacat ?? 0,
                'echo_amplitude' => $request->amplitudo_gema,
            ]);

            return redirect()
                ->route('ultrasonic.analysis.result', ['id' => $idInspeksi])
                ->with('success', 'Data berhasil diperbarui!');
        } catch (Throwable $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error: ' . $exception->getMessage());
        }
    }
}
