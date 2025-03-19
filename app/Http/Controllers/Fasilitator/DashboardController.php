<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\HasilSimulasiCpns;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\HasilSimulasiPppkTeknis;
use App\Models\HasilSimulasiPppkWawancara;
use App\Models\HasilSimulasiPppkManajerial;

class DashboardController extends Controller
{
    public function index()
    {
        $url = "http://localhost:8001/";
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

        // leaderboard simulasi cpns
        $hasil_simulasi_cpns = HasilSimulasiCpns::with('biodata')
            ->whereRelation('biodata', 'id_skpd', 'like', auth()->user()->id_skpd . '%')
            ->orderByDesc('nilai_total')
            ->limit(5)
            ->get();

        // leaderboard simulasi pppk teknis
        $hasil_simulasi_teknis = HasilSimulasiPppkTeknis::with('biodata')
            ->whereRelation('biodata', 'id_skpd', 'like', auth()->user()->id_skpd . '%')
            ->orderByDesc('nilai_total')
            ->limit(5)
            ->get();

        // leaderboard simulasi pppk manajerial
        $hasil_simulasi_manajerial = HasilSimulasiPppkManajerial::with('biodata')
            ->whereRelation('biodata', 'id_skpd', 'like', auth()->user()->id_skpd . '%')
            ->orderByDesc('nilai_total')
            ->limit(5)
            ->get();

        // leaderboard simulasi pppk wawancara
        $hasil_simulasi_wawancara = HasilSimulasiPppkWawancara::with('biodata')
            ->whereRelation('biodata', 'id_skpd', 'like', auth()->user()->id_skpd . '%')
            ->orderByDesc('nilai_total')
            ->limit(5)
            ->get();

        return view('fasilitator.dashboard.index', compact(
            'response',
            'pengumuman',
            'hasil_simulasi_cpns',
            'hasil_simulasi_teknis',
            'hasil_simulasi_manajerial',
            'hasil_simulasi_wawancara'
        ));
    }
}
