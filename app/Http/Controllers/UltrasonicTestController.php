<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUltrasonicTestRequest;
use App\Services\UltrasonicTestService;
use Illuminate\Http\JsonResponse;
use Throwable;

class UltrasonicTestController extends Controller
{
    public function __construct(
        private readonly UltrasonicTestService $ultrasonicTestService
    ) {
    }

    public function store(StoreUltrasonicTestRequest $request, int $idInspeksi): JsonResponse
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
}
