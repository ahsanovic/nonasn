<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Illuminate\Http\Request;
use App\Models\Biodata;
use App\Http\Controllers\Controller;

class StatsPegawaiController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->skpd) {
            $total = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereAktif('Y')->count();    
            $pttpk = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereAktif('Y')->whereJenis_ptt_id('1')->count();    
            $ptt_cabdin = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereAktif('Y')->whereJenis_ptt_id('2')->count();    
            $ptt_sekolah = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereAktif('Y')->whereJenis_ptt_id('3')->count();    
            $gtt = Biodata::where('id_skpd', 'like', auth()->user()->id_skpd . '%')->whereAktif('Y')->whereJenis_ptt_id('4')->count();            
            $unit_kerja = Skpd::with('biodata')->select('id', 'name')->where('id', 'like', auth()->user()->id_skpd . '%')->paginate(20);
            
        } else {
            $id_skpd = explode(" - ", $request->skpd)[0];
            $total = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereAktif('Y')->count();    
            $pttpk = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereAktif('Y')->whereJenis_ptt_id('1')->count();    
            $ptt_cabdin = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereAktif('Y')->whereJenis_ptt_id('2')->count();    
            $ptt_sekolah = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereAktif('Y')->whereJenis_ptt_id('3')->count();    
            $gtt = Biodata::where('id_skpd', 'like', $id_skpd . '%')->whereAktif('Y')->whereJenis_ptt_id('4')->count();
            $unit_kerja = Skpd::with('biodata')->select('id', 'name')->where('id', 'like', $id_skpd . '%')->paginate(20);
        }

        $skpd = Skpd::where('id', auth()->user()->id_skpd)->first(['id', 'name']);

        return view('fasilitator.statistik.jml_pegawai', compact(
            'total',
            'pttpk',
            'ptt_cabdin',
            'ptt_sekolah',
            'gtt',
            'skpd',
            'unit_kerja',
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
