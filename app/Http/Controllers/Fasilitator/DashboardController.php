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
        $web_url = "https://bkd.jatimprov.go.id/";
        $fetch = Http::get('https://siasn.bkd.jatimprov.go.id/pemprov-api/web/berita');

        // if ($fetch->failed()) abort(500);
        $response = json_decode($fetch->body());

        // leaderboard simulasi cpns
        $hasil_simulasi_cpns = HasilSimulasiCpns::with('pegawai')
                            ->whereRelation('pegawai', 'id_skpd', 'like', auth()->user()->id_skpd . '%')
                            ->orderByDesc('nilai_total')
                            ->limit(10)
                            ->get();

        // leaderboard simulasi pppk teknis
        $hasil_simulasi_teknis = HasilSimulasiPppkTeknis::with('pegawai')
                            ->whereRelation('pegawai', 'id_skpd', 'like', auth()->user()->id_skpd . '%')
                            ->orderByDesc('nilai_total')
                            ->limit(10)
                            ->get();
        
        // leaderboard simulasi pppk manajerial
        $hasil_simulasi_manajerial = HasilSimulasiPppkManajerial::with('pegawai')
                            ->whereRelation('pegawai', 'id_skpd', 'like', auth()->user()->id_skpd . '%')
                            ->orderByDesc('nilai_total')
                            ->limit(10)
                            ->get();
        
        // leaderboard simulasi pppk wawancara
        $hasil_simulasi_wawancara = HasilSimulasiPppkWawancara::with('pegawai')
                            ->whereRelation('pegawai', 'id_skpd', 'like', auth()->user()->id_skpd . '%')
                            ->orderByDesc('nilai_total')
                            ->limit(10)
                            ->get();

        return view('fasilitator.dashboard.index', compact(
            'response',
            'web_url',
            'hasil_simulasi_cpns',
            'hasil_simulasi_teknis',
            'hasil_simulasi_manajerial',
            'hasil_simulasi_wawancara'
        ));
    }
}
