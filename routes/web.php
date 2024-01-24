<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Fasilitator\{
    AnakController,
    LoginController,
    DashboardController,
    PegawaiBaruController,
    PegawaiController,
    PegawaiAktifController,
    PegawaiNonAktifController,
    TreeviewController,
    UserFasilitatorController,
    UserNonAsnController,
    SuamiIstriController,
    JabatanController,
    PendidikanPtController,
    PendidikanSmaController,
    PenilaianController,
    DokumenPribadiController,
    DownloadPegawaiController,
    StatsPegawaiController,
    StatsAgamaController,
    StatsPendidikanController,
    UnitKerjaController,
    LogFasilitatorController,
    LogNonAsnController,
    UpdatePasswordController
};
use App\Http\Controllers\NonAsn\{
    NonasnKunciCpnsController,
    NonasnKunciPppkController,
    NonasnLoginController,
    NonasnDashboardController,
    NonasnPegawaiController,
    NonasnSuamiIstriController,
    NonasnAnakController,
    NonasnJabatanController,
    NonasnPendidikanPtController,
    NonasnPenilaianController,
    NonasnPendidikanSmaController,
    NonasnDokumenPribadiController,
    NonasnSimulasiCpnsController,
    NonasnSimulasiPppkController,
    NonasnUpdatePasswordController,
};

/* Fasilitator */
Route::prefix('fasilitator')->group(function() {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('fasilitator.login')->middleware('guest:fasilitator');
    Route::post('/', [LoginController::class, 'login']);
    Route::get('image/{image?}', [PegawaiController::class, 'viewImage'])->name('pegawai.image');
    Route::middleware(['auth:fasilitator', 'revalidate'])->group(function() {
        // dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('fasilitator.dashboard');
        
        // data pegawai
        Route::get('pegawai-baru', [PegawaiBaruController::class, 'index'])->name('pegawaibaru')->middleware('role:admin');
        Route::get('pegawai-baru/unor', [PegawaiBaruController::class, 'unor'])->name('unor')->middleware('role:admin');
        Route::post('pegawai-baru', [PegawaiBaruController::class, 'store'])->name('pegawaibaru.store')->middleware('role:admin');
        Route::get('treeview', [TreeviewController::class, 'index'])->name('treeview');
        Route::get('treeview/unor', [TreeviewController::class, 'unor'])->name('treeview.skpd');
        Route::get('treeview/unor-skpd', [TreeviewController::class, 'unorNoLink'])->name('treeview.skpd.nolink');
        
        // user fasilitator
        Route::get('user-fasilitator', [UserFasilitatorController::class, 'index'])->name('fasilitator.user')->middleware('role:admin');
        Route::get('user-fasilitator/create', [UserFasilitatorController::class, 'create'])->name('fasilitator.user.create')->middleware('role:admin');
        Route::get('user-fasilitator/{username}/edit', [UserFasilitatorController::class, 'edit'])->name('fasilitator.user.edit')->middleware('role:admin');
        Route::post('user-fasilitator', [UserFasilitatorController::class, 'store'])->name('fasilitator.user.store')->middleware('role:admin');
        Route::put('user-fasilitator', [UserFasilitatorController::class, 'update'])->name('fasilitator.user.update')->middleware('role:admin');
        Route::delete('user-fasilitator/{username}', [UserFasilitatorController::class, 'destroy'])->name('fasilitator.user.destroy')->middleware('role:admin');
        
        // user non asn
        Route::get('user-nonasn', [UserNonAsnController::class, 'index'])->name('fasilitator.user-nonasn');
        Route::get('user-nonasn/{username}/edit', [UserNonAsnController::class, 'edit'])->name('fasilitator.user-nonasn.edit');
        Route::put('user-nonasn', [UserNonAsnController::class, 'update'])->name('fasilitator.user-nonasn.update');
        Route::post('user-nonasn/autocomplete', [UserNonAsnController::class, 'autocomplete'])->name('fasilitator.user-nonasn.autocomplete');

        // biodata
        Route::get('pegawai/{idSkpd}', [PegawaiController::class, 'index'])->name('fasilitator.pegawai');
        Route::post('pegawai/autocomplete', [PegawaiController::class, 'autocomplete'])->name('autocomplete');
        Route::get('pegawai/{idSkpd}/biodata/{id}', [PegawaiController::class, 'show'])->name('fasilitator.pegawai.show');
        Route::put('pegawai', [PegawaiController::class, 'update'])->name('fasilitator.pegawai.update');

        // suami/istri
        Route::get('pegawai/{idSkpd}/suami-istri/{id}', [SuamiIstriController::class, 'index'])->name('fasilitator.suami-istri');
        Route::get('pegawai/{idSkpd}/suami-istri/{id}/create', [SuamiIstriController::class, 'create'])->name('fasilitator.suami-istri.create');
        Route::get('pegawai/{idSkpd}/suami-istri/{id}/edit/{idSuamiIstri}', [SuamiIstriController::class, 'edit'])->name('fasilitator.suami-istri.edit');
        Route::post('pegawai/{idSkpd}/suami-istri/{id}', [SuamiIstriController::class, 'store'])->name('fasilitator.suami-istri.store');
        Route::put('pegawai/{idSkpd}/suami-istri/{id}', [SuamiIstriController::class, 'update'])->name('fasilitator.suami-istri.update');
        Route::put('suami-istri/{id}', [SuamiIstriController::class, 'activate'])->name('fasilitator.suami-istri.activate');
        Route::delete('suami-istri/{id}', [SuamiIstriController::class, 'destroy'])->name('fasilitator.suami-istri.destroy');
        Route::get('suami-istri/{file}', [SuamiIstriController::class, 'viewFile'])->name('suami-istri.file');
        
        // anak
        Route::get('pegawai/{idSkpd}/anak/{id}', [AnakController::class, 'index'])->name('fasilitator.anak');
        Route::get('pegawai/{idSkpd}/anak/{id}/create', [AnakController::class, 'create'])->name('fasilitator.anak.create');
        Route::post('pegawai/{idSkpd}/anak/{id}', [AnakController::class, 'store'])->name('fasilitator.anak.store');
        Route::get('pegawai/{idSkpd}/anak/{id}/edit/{idAnak}', [AnakController::class, 'edit'])->name('fasilitator.anak.edit');
        Route::put('pegawai/{idSkpd}/anak/{id}', [AnakController::class, 'update'])->name('fasilitator.anak.update');
        Route::delete('anak/{id}', [AnakController::class, 'destroy'])->name('fasilitator.anak.destroy');
        Route::get('anak/{file}', [AnakController::class, 'viewFile'])->name('anak.file');

        // jabatan
        Route::get('pegawai/{idSkpd}/jabatan/{id}', [JabatanController::class, 'index'])->name('fasilitator.jabatan');
        Route::get('pegawai/{idSkpd}/jabatan/{id}/create', [JabatanController::class, 'create'])->name('fasilitator.jabatan.create');
        Route::post('pegawai/{idSkpd}/jabatan/{id}', [JabatanController::class, 'store'])->name('fasilitator.jabatan.store');
        Route::get('pegawai/{idSkpd}/jabatan/{id}/edit/{idJabatan}', [JabatanController::class, 'edit'])->name('fasilitator.jabatan.edit');
        Route::put('pegawai/{idSkpd}/jabatan/{id}', [JabatanController::class, 'update'])->name('fasilitator.jabatan.update');
        Route::put('jabatan/{id}', [JabatanController::class, 'activate'])->name('fasilitator.jabatan.activate');
        Route::delete('jabatan/{id}', [JabatanController::class, 'destroy'])->name('fasilitator.jabatan.destroy');
        Route::get('jabatan/treeview', [JabatanController::class, 'treeview'])->name('jabatan.treeview');
        Route::get('jabatan/{file}', [JabatanController::class, 'viewFile'])->name('jabatan.file');
        Route::post('autocomplete', [JabatanController::class, 'autocomplete'])->name('jabatan.autocomplete');

        // penilaian
        Route::get('pegawai/{idSkpd}/penilaian/{id}', [PenilaianController::class, 'index'])->name('fasilitator.penilaian');
        Route::get('pegawai/{idSkpd}/penilaian/{id}/create', [PenilaianController::class, 'create'])->name('fasilitator.penilaian.create');
        Route::post('pegawai/{idSkpd}/penilaian/{id}', [PenilaianController::class, 'store'])->name('fasilitator.penilaian.store');
        Route::get('pegawai/{idSkpd}/penilaian/{id}/edit/{idPenilaian}', [PenilaianController::class, 'edit'])->name('fasilitator.penilaian.edit');
        Route::put('pegawai/{idSkpd}/penilaian/{id}', [PenilaianController::class, 'update'])->name('fasilitator.penilaian.update');
        Route::delete('penilaian/{id}', [PenilaianController::class, 'destroy'])->name('fasilitator.penilaian.destroy');
        Route::get('penilaian/{file}', [PenilaianController::class, 'viewFile'])->name('penilaian.file');

        // pendidikan
        /* SD-SMA */
        Route::get('pegawai/{idSkpd}/pendidikan/{id}', [PendidikanSmaController::class, 'index'])->name('fasilitator.pendidikan-sma');
        Route::get('pegawai/{idSkpd}/pendidikan/{id}/create/sma', [PendidikanSmaController::class, 'create'])->name('fasilitator.pendidikan.create-sma');
        Route::post('pegawai/{idSkpd}/pendidikan/{id}/sma', [PendidikanSmaController::class, 'store'])->name('fasilitator.pendidikan.store-sma');
        Route::get('pegawai/{idSkpd}/pendidikan/{id}/edit/{idPendidikan}/sma', [PendidikanSmaController::class, 'edit'])->name('fasilitator.pendidikan.edit-sma');
        Route::put('pegawai/{idSkpd}/pendidikan/{id}/sma', [PendidikanSmaController::class, 'update'])->name('fasilitator.pendidikan.update-sma');
        Route::put('pendidikan/{id}/sma', [PendidikanSmaController::class, 'activate'])->name('fasilitator.pendidikan.activate-sma');
        Route::delete('pendidikan/{id}/sma', [PendidikanSmaController::class, 'destroy'])->name('fasilitator.pendidikan.destroy-sma');
        Route::get('pendidikan/ijazah-sma/{file}', [PendidikanSmaController::class, 'viewFileIjazah'])->name('pendidikan.file-ijazah-sma');
        Route::get('pendidikan/nilai-sma/{file}', [PendidikanSmaController::class, 'viewFileTranskrip'])->name('pendidikan.file-transkrip-sma');
        /* Perguruan Tinggi */
        Route::get('pegawai/{idSkpd}/pendidikan/{id}/create/pt', [PendidikanPtController::class, 'create'])->name('fasilitator.pendidikan.create-pt');
        Route::post('pegawai/{idSkpd}/pendidikan/{id}/pt', [PendidikanPtController::class, 'store'])->name('fasilitator.pendidikan.store-pt');
        Route::get('pegawai/{idSkpd}/pendidikan/{id}/edit/{idPendidikan}/pt', [PendidikanPtController::class, 'edit'])->name('fasilitator.pendidikan.edit-pt');
        Route::put('pegawai/{idSkpd}/pendidikan/{id}/pt', [PendidikanPtController::class, 'update'])->name('fasilitator.pendidikan.update-pt');
        Route::put('pendidikan/{id}/pt', [PendidikanPtController::class, 'activate'])->name('fasilitator.pendidikan.activate-pt');
        Route::delete('pendidikan/{id}/pt', [PendidikanPtController::class, 'destroy'])->name('fasilitator.pendidikan.destroy-pt');
        Route::get('pendidikan/ijazah-pt/{file}', [PendidikanPtController::class, 'viewFileIjazah'])->name('pendidikan.file-ijazah-pt');
        Route::get('pendidikan/nilai-pt/{file}', [PendidikanPtController::class, 'viewFileTranskrip'])->name('pendidikan.file-transkrip-pt');

        // dokumen pribadi
        Route::get('pegawai/{idSkpd}/dok-pribadi/{id}', [DokumenPribadiController::class, 'index'])->name('fasilitator.dok-pribadi');
        Route::get('pegawai/{idSkpd}/dok-pribadi/{id}/edit/{idDokumen}/{field}', [DokumenPribadiController::class, 'edit'])->name('fasilitator.dok-pribadi.edit');
        Route::put('pegawai/{idSkpd}/dok-pribadi/{id}', [DokumenPribadiController::class, 'update'])->name('fasilitator.dok-pribadi.update');
        // Route::delete('dok-pribadi/{id}', [DokumenPribadiController::class, 'destroy'])->name('fasilitator.dok-pribadi.destroy');
        Route::get('dok-pribadi/{file}', [DokumenPribadiController::class, 'viewFile'])->name('dok-pribadi.file');

        // unit kerja
        Route::get('unit-kerja', [UnitKerjaController::class, 'index'])->name('fasilitator.unit-kerja')->middleware('role:admin');
        Route::get('unit-kerja/create', [UnitKerjaController::class, 'create'])->name('fasilitator.unit-kerja.create')->middleware('role:admin');
        Route::post('unit-kerja/create', [UnitKerjaController::class, 'store'])->name('fasilitator.unit-kerja.store')->middleware('role:admin');
        Route::get('unit-kerja/treeview', [UnitKerjaController::class, 'unor'])->name('fasilitator.treeview-unor')->middleware('role:admin');
        Route::get('unit-kerja/{idSkpd}/edit', [UnitKerjaController::class, 'edit'])->name('fasilitator.unit-kerja.edit')->middleware('role:admin');
        Route::put('unit-kerja/{idSkpd}', [UnitKerjaController::class, 'update'])->name('fasilitator.unit-kerja.update')->middleware('role:admin');
        Route::delete('unit-kerja/{idSkpd}', [UnitKerjaController::class, 'destroy'])->name('fasilitator.unit-kerja.destroy')->middleware('role:admin');

        //statistik
        Route::get('jml-pegawai', [StatsPegawaiController::class, 'index'])->name('stats.jml-pegawai');
        Route::get('jml-pegawai/unor', [StatsPegawaiController::class, 'unor'])->name('stats-pegawai.unor');
        Route::get('stats-agama', [StatsAgamaController::class, 'index'])->name('stats.agama');
        Route::get('stats-agama/unor', [StatsAgamaController::class, 'unor'])->name('stats-agama.unor');
        Route::get('stats-pendidikan', [StatsPendidikanController::class, 'index'])->name('stats.pendidikan');
        Route::get('stats-pendidikan/unor', [StatsPendidikanController::class, 'unor'])->name('stats-pendidikan.unor');

        // aktivasi/deaktivasi
        Route::get('pegawai-nonaktif', [PegawaiNonAktifController::class, 'index'])->name('fasilitator.pegawai-nonaktif')->middleware('role:admin');
        Route::post('autocomplete-nonaktif', [PegawaiNonAktifController::class, 'autocomplete'])->name('autocomplete-nonaktif')->middleware('role:admin');
        Route::put('pegawai-nonaktif', [PegawaiNonAktifController::class, 'aktivasi'])->name('fasilitator.aktivasi-pegawai')->middleware('role:admin');
        Route::get('pegawai-aktif', [PegawaiAktifController::class, 'index'])->name('fasilitator.pegawai-aktif')->middleware('role:admin');
        Route::post('autocomplete-aktif', [PegawaiAktifController::class, 'autocomplete'])->name('autocomplete-aktif')->middleware('role:admin');
        Route::put('pegawai-aktif', [PegawaiAktifController::class, 'deaktivasi'])->name('fasilitator.deaktivasi-pegawai')->middleware('role:admin');

        // rekap log
        Route::get('rekap-log-fasilitator', [LogFasilitatorController::class, 'index'])->name('fasilitator.rekap-log-fasilitator')->middleware('role:admin');
        Route::get('rekap-log-nonasn', [LogNonAsnController::class, 'index'])->name('fasilitator.rekap-log-nonasn')->middleware('role:admin');

        // download
        Route::post('download-pegawai/{idSkpd}', [DownloadPegawaiController::class, 'download'])->name('fasilitator.download-pegawai');
        Route::post('download/{idSkpd?}', [DownloadPegawaiController::class, 'download'])->name('fasilitator.download-pegawai-stats');
        Route::post('download-person/{idPegawai}', [DownloadPegawaiController::class, 'downloadPerson'])->name('fasilitator.download-person');
        Route::post('download-pttpk/{idSkpd?}', [DownloadPegawaiController::class, 'downloadPttpk'])->name('fasilitator.download-pttpk');
        Route::post('download-pttc/{idSkpd?}', [DownloadPegawaiController::class, 'downloadPttCabdin'])->name('fasilitator.download-pttcabdin');
        Route::post('download-ptts/{idSkpd?}', [DownloadPegawaiController::class, 'downloadPttSekolah'])->name('fasilitator.download-pttsekolah');
        Route::post('download-gtt/{idSkpd?}', [DownloadPegawaiController::class, 'downloadGtt'])->name('fasilitator.download-gtt');
        Route::post('download-by-agama/{idSkpd?}', [DownloadPegawaiController::class, 'downloadByAgama'])->name('fasilitator.download-by-agama');
        Route::get('download-data-anak', [DownloadPegawaiController::class, 'downloadDataAnak'])->name('fasilitator.download-data-anak');
        Route::get('download-data-pasangan', [DownloadPegawaiController::class, 'downloadDataPasangan'])->name('fasilitator.download-data-pasangan');

        // update password
        Route::get('update-password', [UpdatePasswordController::class, 'index'])->name('fasilitator.password');
        Route::put('update-password', [UpdatePasswordController::class, 'update'])->name('fasilitator.password.update');

        // logout
        Route::post('/logout', [LoginController::class, 'logout'])->name('fasilitator.logout');
    });
});

/* Personal */
Route::get('/', [NonasnLoginController::class, 'showLoginForm'])->name('nonasn.login')->middleware('guest:nonasn');
Route::post('/', [NonasnLoginController::class, 'login']);
Route::get('image/{image?}', [NonasnPegawaiController::class, 'viewImage'])->name('nonasn.image');
Route::middleware(['auth:nonasn', 'revalidate'])->group(function() {
    // dashboard
    Route::get('dashboard', [NonasnDashboardController::class, 'index'])->name('nonasn.dashboard');
    
    // biodata
    Route::get('biodata', [NonasnPegawaiController::class, 'index'])->name('nonasn.biodata');
    Route::put('biodata', [NonasnPegawaiController::class, 'update'])->name('nonasn.biodata.update');

    // suami/istri
    Route::get('suami-istri', [NonasnSuamiIstriController::class, 'index'])->name('nonasn.suami-istri');
    Route::get('suami-istri/create', [NonasnSuamiIstriController::class, 'create'])->name('nonasn.suami-istri.create');
    Route::get('suami-istri/edit/{id}', [NonasnSuamiIstriController::class, 'edit'])->name('nonasn.suami-istri.edit');
    Route::post('suami-istri', [NonasnSuamiIstriController::class, 'store'])->name('nonasn.suami-istri.store');
    Route::put('suami-istri/{id}', [NonasnSuamiIstriController::class, 'update'])->name('nonasn.suami-istri.update');
    Route::put('suami-istri/{id}/activate', [NonasnSuamiIstriController::class, 'activate'])->name('nonasn.suami-istri.activate');
    Route::delete('suami-istri/{id}', [NonasnSuamiIstriController::class, 'destroy'])->name('nonasn.suami-istri.destroy');
    Route::get('suami-istri/{file}', [NonasnSuamiIstriController::class, 'viewFile'])->name('nonasn.suami-istri.file');
    
    // anak
    Route::get('anak', [NonasnAnakController::class, 'index'])->name('nonasn.anak');
    Route::get('anak/create', [NonasnAnakController::class, 'create'])->name('nonasn.anak.create');
    Route::post('anak', [NonasnAnakController::class, 'store'])->name('nonasn.anak.store');
    Route::get('anak/{id}/edit', [NonasnAnakController::class, 'edit'])->name('nonasn.anak.edit');
    Route::put('anak/{id}', [NonasnAnakController::class, 'update'])->name('nonasn.anak.update');
    Route::delete('anak/{id}', [NonasnAnakController::class, 'destroy'])->name('nonasn.anak.destroy');
    Route::get('anak/{file}', [NonasnAnakController::class, 'viewFile'])->name('nonasn.anak.file');

    // jabatan
    Route::get('jabatan', [NonasnJabatanController::class, 'index'])->name('nonasn.jabatan');
    Route::get('jabatan/create', [NonasnJabatanController::class, 'create'])->name('nonasn.jabatan.create');
    Route::post('jabatan', [NonasnJabatanController::class, 'store'])->name('nonasn.jabatan.store');
    Route::get('jabatan/{id}/edit', [NonasnJabatanController::class, 'edit'])->name('nonasn.jabatan.edit');
    Route::put('jabatan/{id}', [NonasnJabatanController::class, 'update'])->name('nonasn.jabatan.update');
    Route::put('jabatan/{id}/activate', [NonasnJabatanController::class, 'activate'])->name('nonasn.jabatan.activate');
    Route::delete('jabatan/{id}', [NonasnJabatanController::class, 'destroy'])->name('nonasn.jabatan.destroy');
    Route::get('jabatan/treeview', [NonasnJabatanController::class, 'treeview'])->name('nonasn.jabatan.treeview');
    Route::get('jabatan/{file}', [NonasnJabatanController::class, 'viewFile'])->name('nonasn.jabatan.file');
    Route::post('autocomplete', [NonasnJabatanController::class, 'autocomplete'])->name('nonasn.jabatan.autocomplete');

    // penilaian
    Route::get('penilaian', [NonasnPenilaianController::class, 'index'])->name('nonasn.penilaian');
    Route::get('penilaian/create', [NonasnPenilaianController::class, 'create'])->name('nonasn.penilaian.create');
    Route::post('penilaian', [NonasnPenilaianController::class, 'store'])->name('nonasn.penilaian.store');
    Route::get('penilaian/{id}/edit', [NonasnPenilaianController::class, 'edit'])->name('nonasn.penilaian.edit');
    Route::put('penilaian/{id}', [NonasnPenilaianController::class, 'update'])->name('nonasn.penilaian.update');
    Route::delete('penilaian/{id}', [NonasnPenilaianController::class, 'destroy'])->name('nonasn.penilaian.destroy');
    Route::get('penilaian/{file}', [NonasnPenilaianController::class, 'viewFile'])->name('nonasn.penilaian.file');

    // pendidikan
    /* SD-SMA */
    Route::get('pendidikan', [NonasnPendidikanSmaController::class, 'index'])->name('nonasn.pendidikan-sma');
    Route::get('pendidikan/create/sma', [NonasnPendidikanSmaController::class, 'create'])->name('nonasn.pendidikan.create-sma');
    Route::post('pendidikan/sma', [NonasnPendidikanSmaController::class, 'store'])->name('nonasn.pendidikan.store-sma');
    Route::get('pendidikan/{id}/edit/sma', [NonasnPendidikanSmaController::class, 'edit'])->name('nonasn.pendidikan.edit-sma');
    Route::put('pendidikan/{id}/sma', [NonasnPendidikanSmaController::class, 'update'])->name('nonasn.pendidikan.update-sma');
    Route::put('pendidikan/{id}/sma/activate', [NonasnPendidikanSmaController::class, 'activate'])->name('nonasn.pendidikan.activate-sma');
    Route::delete('pendidikan/{id}/sma', [NonasnPendidikanSmaController::class, 'destroy'])->name('nonasn.pendidikan.destroy-sma');
    Route::get('pendidikan/ijazah-sma/{file}', [NonasnPendidikanSmaController::class, 'viewFileIjazah'])->name('nonasn.pendidikan.file-ijazah-sma');
    Route::get('pendidikan/nilai-sma/{file}', [NonasnPendidikanSmaController::class, 'viewFileTranskrip'])->name('nonasn.pendidikan.file-transkrip-sma');
    /* Perguruan Tinggi */
    Route::get('pendidikan/create/pt', [NonasnPendidikanPtController::class, 'create'])->name('nonasn.pendidikan.create-pt');
    Route::post('pendidikan/pt', [NonasnPendidikanPtController::class, 'store'])->name('nonasn.pendidikan.store-pt');
    Route::get('pendidikan/{id}/edit/pt', [NonasnPendidikanPtController::class, 'edit'])->name('nonasn.pendidikan.edit-pt');
    Route::put('pendidikan/{id}/pt', [NonasnPendidikanPtController::class, 'update'])->name('nonasn.pendidikan.update-pt');
    Route::put('pendidikan/{id}/pt/activate', [NonasnPendidikanPtController::class, 'activate'])->name('nonasn.pendidikan.activate-pt');
    Route::delete('pendidikan/{id}/pt', [NonasnPendidikanPtController::class, 'destroy'])->name('nonasn.pendidikan.destroy-pt');
    Route::get('pendidikan/ijazah-pt/{file}', [NonasnPendidikanPtController::class, 'viewFileIjazah'])->name('nonasn.pendidikan.file-ijazah-pt');
    Route::get('pendidikan/nilai-pt/{file}', [NonasnPendidikanPtController::class, 'viewFileTranskrip'])->name('nonasn.pendidikan.file-transkrip-pt');

    // dokumen pribadi
    Route::get('dok-pribadi', [NonasnDokumenPribadiController::class, 'index'])->name('nonasn.dok-pribadi');
    Route::get('dok-pribadi/{id}/edit/{field}', [NonasnDokumenPribadiController::class, 'edit'])->name('nonasn.dok-pribadi.edit');
    Route::put('dok-pribadi/{id}', [NonasnDokumenPribadiController::class, 'update'])->name('nonasn.dok-pribadi.update');
    Route::get('dok-pribadi/{file}', [NonasnDokumenPribadiController::class, 'viewFile'])->name('nonasn.dok-pribadi.file');

    // simulasi tes cpns
    Route::prefix('simulasi-cpns')->group(function() {
        Route::get('/', [NonasnSimulasiCpnsController::class, 'index'])->name('nonasn.simulasi.cpns');
        Route::get('ujian/{id}', [NonasnSimulasiCpnsController::class, 'show'])->name('nonasn.simulasi.cpns.show');
        Route::post('/', [NonasnSimulasiCpnsController::class, 'store'])->name('nonasn.simulasi.cpns.store');
        Route::put('ujian/{id}', [NonasnSimulasiCpnsController::class, 'update'])->name('nonasn.simulasi.cpns.update');
        Route::delete('ujian/{idUjian}', [NonasnSimulasiCpnsController::class, 'destroy'])->name('nonasn.simulasi.cpns.destroy');
        Route::get('kunci/{no}', [NonasnKunciCpnsController::class, 'index'])->name('nonasn.simulasi.cpns.kunci');
    });
    
    // simulasi tes pppk
    Route::prefix('simulasi-pppk')->group(function() {
        Route::get('/', [NonasnSimulasiPppkController::class, 'index'])->name('nonasn.simulasi.pppk');
        Route::get('/ujian-teknis/hasil', [NonasnSimulasiPppkController::class, 'hasilTeknis'])->name('nonasn.simulasi.pppk.hasil-teknis');
        Route::patch('/jabatan', [NonasnSimulasiPppkController::class, 'updateJabatan'])->name('nonasn.simulasi.pppk.update-jabatan');
        Route::post('/', [NonasnSimulasiPppkController::class, 'storeTeknis'])->name('nonasn.simulasi.pppk.store-teknis');
        Route::get('/ujian-teknis/{id}', [NonasnSimulasiPppkController::class, 'showTeknis'])->name('nonasn.simulasi.pppk.show-teknis');
        Route::put('ujian-teknis/{id}', [NonasnSimulasiPppkController::class, 'updateTeknis'])->name('nonasn.simulasi.pppk.update-teknis');
        Route::delete('ujian-teknis/{idUjian}', [NonasnSimulasiPppkController::class, 'destroyTeknis'])->name('nonasn.simulasi.pppk.destroy-teknis');
        Route::get('kunci-teknis/{no}', [NonasnKunciPppkController::class, 'teknis'])->name('nonasn.simulasi.pppk.kunci-teknis');

        Route::get('/ujian-mansoskul/hasil', [NonasnSimulasiPppkController::class, 'hasilMansoskul'])->name('nonasn.simulasi.pppk.hasil-mansoskul');
        Route::post('/ujian-mansoskul', [NonasnSimulasiPppkController::class, 'storeMansoskul'])->name('nonasn.simulasi.pppk.store-mansoskul');
        Route::get('/ujian-mansoskul/{id}', [NonasnSimulasiPppkController::class, 'showMansoskul'])->name('nonasn.simulasi.pppk.show-mansoskul');
        Route::put('ujian-mansoskul/{id}', [NonasnSimulasiPppkController::class, 'updateMansoskul'])->name('nonasn.simulasi.pppk.update-mansoskul');
        Route::delete('ujian-mansoskul/{idUjian}', [NonasnSimulasiPppkController::class, 'destroyMansoskul'])->name('nonasn.simulasi.pppk.destroy-mansoskul');
        Route::get('kunci-mansoskul/{no}', [NonasnKunciPppkController::class, 'mansoskul'])->name('nonasn.simulasi.pppk.kunci-mansoskul');

        Route::get('/ujian-wawancara/hasil', [NonasnSimulasiPppkController::class, 'hasilWawancara'])->name('nonasn.simulasi.pppk.hasil-wawancara');
        Route::post('/ujian-wawancara', [NonasnSimulasiPppkController::class, 'storeWawancara'])->name('nonasn.simulasi.pppk.store-wawancara');
        Route::get('/ujian-wawancara/{id}', [NonasnSimulasiPppkController::class, 'showWawancara'])->name('nonasn.simulasi.pppk.show-wawancara');
        Route::put('ujian-wawancara/{id}', [NonasnSimulasiPppkController::class, 'updateWawancara'])->name('nonasn.simulasi.pppk.update-wawancara');
        Route::delete('ujian-wawancara/{idUjian}', [NonasnSimulasiPppkController::class, 'destroyWawancara'])->name('nonasn.simulasi.pppk.destroy-wawancara');
        Route::get('kunci-wawancara/{no}', [NonasnKunciPppkController::class, 'wawancara'])->name('nonasn.simulasi.pppk.kunci-wawancara');
    });

    // update password
    Route::get('update-password', [NonasnUpdatePasswordController::class, 'index'])->name('nonasn.password');
    Route::put('update-password', [NonasnUpdatePasswordController::class, 'update'])->name('nonasn.password.update');

    // logout
    Route::post('/logout', [NonasnLoginController::class, 'logout'])->name('nonasn.logout');
});