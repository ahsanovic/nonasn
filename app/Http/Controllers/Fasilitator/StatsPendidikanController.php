<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Illuminate\Http\Request;
use App\Models\Biodata;
use App\Http\Controllers\Controller;
use App\Models\Jenjang;

class StatsPendidikanController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->skpd) {
            $jenjang = Jenjang::all();
            $s3 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 10)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $s2 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 9)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $s1 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 8)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $d4 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 7)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $d3 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 6)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $d2 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 5)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $d1 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 4)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $slta = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 3)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $sltp = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 2)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $sd = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 1)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();
        } else {
            $id_skpd = explode(" - ", $request->skpd)[0];
            $jenjang = Jenjang::all();
            $s3 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 10)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $s2 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 9)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $s1 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 8)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $d4 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 7)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $d3 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 6)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $d2 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 5)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $d1 = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 4)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $slta = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 3)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $sltp = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 2)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();

            $sd = Biodata::leftJoin('ptt_pendidikan', 'ptt_biodata.id_ptt', '=', 'ptt_pendidikan.id_ptt')
                    ->leftJoin('jenjang', 'ptt_pendidikan.id_jenjang', '=', 'jenjang.id_jenjang')
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('ptt_pendidikan.id_jenjang', 1)
                    ->where('ptt_biodata.aktif', 'Y')
                    ->where('ptt_pendidikan.aktif', 'Y')
                    ->count();
        }

        $skpd = Skpd::where('id', auth()->user()->id_skpd)->first(['id', 'name']);

        return view('fasilitator.statistik.pendidikan', compact(
            'jenjang',
            'skpd',
            's3',
            's2',
            's1',
            'd4',
            'd3',
            'd2',
            'd1',
            'slta',
            'sltp',
            'sd',
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
