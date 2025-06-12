<?php

use App\Http\Controllers\API\v1\DataKeluargaController;
use App\Http\Controllers\API\v1\JabatanController;
use App\Http\Controllers\API\v1\PegawaiController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'client.credentials',
    'json.response',
    'cors',
    'check.scope.org:view-employees',
])
    ->prefix('v1')
    ->group(function () {
        Route::get('client/pegawai', [PegawaiController::class, 'getAllPegawai']);

        Route::get('pegawai', [PegawaiController::class, 'pegawaiAll']);
        Route::get('pegawai/niptt/{niptt}', [PegawaiController::class, 'pegawaiByNip']);
        Route::get('pegawai/skpd/{idSkpd}', [PegawaiController::class, 'pegawaiBkd'])->withoutMiddleware('check.scope.org:view-employees');

        Route::get('data-keluarga', [DataKeluargaController::class, 'getAll']);
        Route::get('data-keluarga/{niptt}', [DataKeluargaController::class, 'getByNip']);

        Route::get('jabatan/{niptt}/{idJabatan?}', [JabatanController::class, 'index']);
        Route::get('jabatan/{file}', [JabatanController::class, 'viewFile']);
    });
