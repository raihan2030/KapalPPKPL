<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUltrasonicTestRequest;
use App\Services\UltrasonicTestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class UltrasonicTestWebController extends Controller
{
    public function __construct(
        private readonly UltrasonicTestService $ultrasonicTestService
    ) {
    }

    public function create(int $idInspeksi): View
    {
        return view('ultrasonic.create', [
            'idInspeksi' => $idInspeksi,
        ]);
    }

    public function store(StoreUltrasonicTestRequest $request, int $idInspeksi): RedirectResponse
    {
        try {
            $this->ultrasonicTestService->store($idInspeksi, $request->validated());

            return redirect()
                ->route('ultrasonic.create', $idInspeksi)
                ->with('success', 'Data ultrasonic test berhasil disimpan.');
        } catch (Throwable $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data ultrasonic test.');
        }
    }
}
