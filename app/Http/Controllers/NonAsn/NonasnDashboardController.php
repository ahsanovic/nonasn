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
        $url = "https://bkd.jatimprov.go.id/";
        $api_key = 'bkd-35XLeFWzT90Efddkp8o1uzpSDZJiNn';

        $fetch = Http::withHeaders([
            'x-api-key' => $api_key,
            'Accept' => 'application/json'
        ])
            ->get($url . 'api/berita');
        $response = json_decode($fetch->body());

        $result = Http::withHeaders([
            'x-api-key' => $api_key,
            'Accept' => 'application/json'
        ])
            ->get($url . 'api/pengumuman-rekrutmen');
        $pengumuman = json_decode($result->body());

        $notif = DownloadPegawai::whereId_ptt(auth()->user()->id_ptt)->firstOrFail();
        $notif_doc = DokumenPribadi::whereId_ptt(auth()->user()->id_ptt)->firstOrFail();

        // leaderboard simulasi cpns
        $hasil_simulasi_cpns = HasilSimulasiCpns::with('biodata')
            ->orderByDesc('nilai_total')
            ->limit(5)
            ->get();

        // leaderboard simulasi pppk teknis
        $hasil_simulasi_teknis = HasilSimulasiPppkTeknis::with('biodata')
            ->orderByDesc('nilai_total')
            ->limit(5)
            ->get();

        // leaderboard simulasi pppk manajerial
        $hasil_simulasi_manajerial = HasilSimulasiPppkManajerial::with('biodata')
            ->orderByDesc('nilai_total')
            ->limit(5)
            ->get();

        // leaderboard simulasi pppk wawancara
        $hasil_simulasi_wawancara = HasilSimulasiPppkWawancara::with('biodata')
            ->orderByDesc('nilai_total')
            ->limit(5)
            ->get();

        return view('nonasn.dashboard.index', compact(
            'response',
            'pengumuman',
            'notif',
            'notif_doc',
            'hasil_simulasi_cpns',
            'hasil_simulasi_teknis',
            'hasil_simulasi_manajerial',
            'hasil_simulasi_wawancara'
        ));
    }
}
