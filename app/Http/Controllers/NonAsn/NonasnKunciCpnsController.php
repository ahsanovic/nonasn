<?php

namespace App\Http\Controllers\NonAsn;

use App\Http\Controllers\Controller;
use App\Models\NonAsn\HistoryCpns;

class NonasnKunciCpnsController extends Controller
{
    public function index($id)
    {
        $ujianId = request()->get('ujian');
        $fetch_data = HistoryCpns::whereId($ujianId)
                        ->whereId_ptt(auth()->user()->id_ptt)
                        ->first();
        
        $jawaban_user = explode(",", $fetch_data->jawaban);
        $kunci = explode(",", $fetch_data->kunci);

        $soal = explode("|", $fetch_data->soal);
        $soal = $soal[$id-1];

        $opsi1 = explode("|", $fetch_data->opsi1);
        $opsi1 = $opsi1[$id-1];

        $opsi2 = explode("|", $fetch_data->opsi2);
        $opsi2 = $opsi2[$id-1];

        $opsi3 = explode("|", $fetch_data->opsi3);
        $opsi3 = $opsi3[$id-1];

        $opsi4 = explode("|", $fetch_data->opsi4);
        $opsi4 = $opsi4[$id-1];
        
        $opsi5 = explode("|", $fetch_data->opsi5);
        $opsi5 = $opsi5[$id-1];

        return view('nonasn.simulasi.cpns.kunci', compact('soal','opsi1','opsi2','opsi3','opsi4','opsi5'))
                ->with('nomor_sekarang', $id)
                ->with('ujianId', $ujianId)
                ->with('kunci', $kunci)
                ->with('jawaban', $jawaban_user);
    }
}
