<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\v1\DataKeluargaController;
use App\Http\Controllers\API\v1\JabatanController;
use App\Http\Controllers\API\v1\PegawaiController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:api')->group(function() {
Route::prefix('v1')->group(function() {
    Route::get('pegawai', [PegawaiController::class, 'pegawaiAll']);
    Route::get('pegawai/{niptt}', [PegawaiController::class, 'pegawaiByNip']);

    Route::get('data-keluarga', [DataKeluargaController::class, 'getAll']);
    Route::get('data-keluarga/{niptt}', [DataKeluargaController::class, 'getByNip']);

    Route::get('jabatan/{niptt}/{idJabatan?}', [JabatanController::class, 'index']);
});
