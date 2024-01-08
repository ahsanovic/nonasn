<?php

namespace App\Http\Controllers\Fasilitator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $web_url = "https://bkd.jatimprov.go.id/";
        $fetch = Http::get('https://siasn.bkd.jatimprov.go.id/pemprov-api/web/berita');

        // if ($fetch->failed()) abort(500);
        $response = json_decode($fetch->body());

        return view('fasilitator.dashboard.index', compact('response', 'web_url'));
    }
}
