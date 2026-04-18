<?php

use App\Http\Controllers\TestSelectionController;
use App\Http\Controllers\UltrasonicTestController;
use App\Http\Controllers\UltrasonicAnalysisController;
use App\Http\Controllers\ValidationController;
use Illuminate\Support\Facades\Route;

// Home page - Test Selection
Route::get('/', [TestSelectionController::class, 'index'])->name('home');
Route::post('/select-test', [TestSelectionController::class, 'selectTest'])->name('test.select');

// Ultrasonic Test Routes (Input data pengujian) ← CUKUP SEKALI SAJA
Route::get('/ultrasonic/{idInspeksi}/create', [UltrasonicTestController::class, 'create'])
    ->name('ultrasonic.create');
Route::post('/ultrasonic/{idInspeksi}', [UltrasonicTestController::class, 'store'])
    ->name('ultrasonic.store');

// Ultrasonic Test Edit & Update Routes
Route::get('/ultrasonic/{idInspeksi}/edit', [UltrasonicTestController::class, 'edit'])
    ->name('ultrasonic.edit');
Route::put('/ultrasonic/{idInspeksi}', [UltrasonicTestController::class, 'update'])
    ->name('ultrasonic.update');

// Halaman form analisis ultrasonic (MILIK ANDA)
Route::get('/ultrasonic-analysis', [UltrasonicAnalysisController::class, 'index'])
    ->name('ultrasonic.analysis.index');

// Proses analisis via AJAX (real-time)
Route::post('/ultrasonic-analyze', [UltrasonicAnalysisController::class, 'analyze'])
    ->name('ultrasonic.analysis.analyze');

// Simpan hasil analisis ke database
Route::post('/ultrasonic-store', [UltrasonicAnalysisController::class, 'store'])
    ->name('ultrasonic.analysis.store');

Route::get('/ultrasonic-analysis/result/{id}', [UltrasonicAnalysisController::class, 'result'])
    ->name('ultrasonic.analysis.result');

// Validation Routes (US4 - Validasi hasil analisis)
Route::get('/ultrasonic-analysis/validate/{idInspeksi}', [ValidationController::class, 'showValidationPage'])
    ->name('ultrasonic.validation.show');
Route::post('/ultrasonic-analysis/validate/{idInspeksi}', [ValidationController::class, 'validateAnalysis'])
    ->name('ultrasonic.validation.validate');
Route::post('/ultrasonic-analysis/reject/{idInspeksi}', [ValidationController::class, 'rejectAnalysis'])
    ->name('ultrasonic.validation.reject');
Route::get('/ultrasonic-analysis/report/{idInspeksi}', [ValidationController::class, 'generateReport'])
    ->name('ultrasonic.validation.report');
