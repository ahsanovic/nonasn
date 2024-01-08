<?php

namespace App\Http\Controllers\NonAsn;

use App\Http\Controllers\Controller;
use App\Models\DokumenPribadi;
use App\Models\Fasilitator\DownloadPegawai;
use Illuminate\Support\Facades\Http;

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

        return view('nonasn.dashboard.index', compact('response', 'web_url', 'notif', 'notif_doc'));
    }
}
