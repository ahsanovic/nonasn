<?php

namespace App\Http\Controllers\NonAsn;

use App\Models\DokumenPribadi;
use App\Models\HasilSimulasiCpns;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\HasilSimulasiPppkTeknis;
use App\Models\HasilSimulasiPppkWawancara;
use App\Models\Fasilitator\DownloadPegawai;
use App\Models\HasilSimulasiPppkManajerial;

class NonasnDashboardController extends Controller
{
    public function index()
    {
        $web_url = "https://bkd.jatimprov.go.id/";
        $fetch = Http::get('https://siasn.bkd.jatimprov.go.id/pemprov-api/web/berita');

        // if ($fetch->failed()) abort(500);
        $response = json_decode($fetch->body());

        $notif = DownloadPegawai::whereId_ptt(auth()->user()->id_ptt)->firstOrFail();
        $notif_doc = DokumenPribadi::whereId_ptt(auth()->user()->id_ptt)->firstOrFail();

        // leaderboard simulasi cpns
        $hasil_simulasi_cpns = HasilSimulasiCpns::with('biodata')
                            ->orderByDesc('nilai_total')
                            ->limit(10)
                            ->get();

        // leaderboard simulasi pppk teknis
        $hasil_simulasi_teknis = HasilSimulasiPppkTeknis::with('biodata')
                            ->orderByDesc('nilai_total')
                            ->limit(10)
                            ->get();
        
        // leaderboard simulasi pppk manajerial
        $hasil_simulasi_manajerial = HasilSimulasiPppkManajerial::with('biodata')
                            ->orderByDesc('nilai_total')
                            ->limit(10)
                            ->get();
        
        // leaderboard simulasi pppk wawancara
        $hasil_simulasi_wawancara = HasilSimulasiPppkWawancara::with('biodata')
                            ->orderByDesc('nilai_total')
                            ->limit(10)
                            ->get();

        return view('nonasn.dashboard.index', compact(
            'response',
            'web_url',
            'notif',
            'notif_doc',
            'hasil_simulasi_cpns',
            'hasil_simulasi_teknis',
            'hasil_simulasi_manajerial',
            'hasil_simulasi_wawancara'
        ));
    }
}
