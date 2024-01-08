<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Illuminate\Http\Request;
use App\Models\Biodata;
use App\Http\Controllers\Controller;
use App\Models\RefAgama;

class StatsAgamaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->skpd) {
            $agama = RefAgama::all();
            $islam = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereId_agama(1)->whereAktif('Y')->count();
            $kristen = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereId_agama(2)->whereAktif('Y')->count();
            $katolik = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereId_agama(3)->whereAktif('Y')->count();
            $hindu = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereId_agama(4)->whereAktif('Y')->count();
            $budha = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereId_agama(5)->whereAktif('Y')->count();
            $konghucu = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereId_agama(6)->whereAktif('Y')->count();
            $lainnya = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereId_agama(7)->whereAktif('Y')->count();            
        } else {
            $id_skpd = explode(" - ", $request->skpd)[0];
            $agama = RefAgama::all();
            $islam = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereId_agama(1)->whereAktif('Y')->count();
            $kristen = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereId_agama(2)->whereAktif('Y')->count();
            $katolik = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereId_agama(3)->whereAktif('Y')->count();
            $hindu = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereId_agama(4)->whereAktif('Y')->count();
            $budha = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereId_agama(5)->whereAktif('Y')->count();
            $konghucu = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereId_agama(6)->whereAktif('Y')->count();
            $lainnya = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereId_agama(7)->whereAktif('Y')->count();
        }

        $skpd = Skpd::where('id', auth()->user()->id_skpd)->first(['id', 'name']);

        return view('fasilitator.statistik.agama', compact(
            'agama',
            'skpd',
            'islam',
            'kristen',
            'katolik',
            'hindu',
            'budha',
            'konghucu',
            'lainnya'
        ));
    }

    public function unor()
    {
        $skpd = Skpd::where('id', 'like', auth()->user()->id_skpd . '%')->get();
        $tree = [];
        foreach ($skpd as $unor) {
            $array = [
                'id' => $unor->id,
                'pId' => $unor->pId,
                'name' => $unor->id . ' - ' . $unor->name,
                'url' => $unor->url,
                'icon' => url('/zTree/css/zTreeStyle/img/diy/1_close.png'),
                'open' => $unor->open
            ];
            array_push($tree, $array);
        }
        return response()->json($tree);
    }
}
