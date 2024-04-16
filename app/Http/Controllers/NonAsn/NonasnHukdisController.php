<?php

namespace App\Http\Controllers\NonAsn;

use App\Http\Controllers\Controller;
use App\Models\Hukdis;

class NonasnHukdisController extends Controller
{
    public function index()
    {
        $data = Hukdis::with('jenisHukdis')
                ->whereId_ptt(auth()->user()->id_ptt)
                ->orderByDesc('tgl_sk')
                ->get();
        return view('nonasn.hukdis.index', compact('data'));
    }

    public function viewFile($file)
    {
        try {
            $file = storage_path('app/upload_hukdis/' . $file);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
