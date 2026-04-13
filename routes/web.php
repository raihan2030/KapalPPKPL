<?php

use App\Http\Controllers\UltrasonicTestWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('ultrasonic.create', ['idInspeksi' => 1]);
});

Route::get('/ultrasonic/{idInspeksi}/create', [UltrasonicTestWebController::class, 'create'])
    ->name('ultrasonic.create');
Route::post('/ultrasonic/{idInspeksi}', [UltrasonicTestWebController::class, 'store'])
    ->name('ultrasonic.store');
