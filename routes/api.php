<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\v1\BpjsController;
use App\Http\Controllers\API\v1\PegawaiController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:api')->group(function() {
Route::prefix('v1')->group(function() {
    Route::get('pegawai', [PegawaiController::class, 'pegawaiAll']);
    Route::get('pegawai/{niptt}', [PegawaiController::class, 'pegawaiByNip']);

    Route::get('bpjs', [BpjsController::class, 'getAll']);
    Route::get('bpjs/{niptt}', [BpjsController::class, 'getByNip']);
});
