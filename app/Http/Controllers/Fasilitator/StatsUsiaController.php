<?php

namespace App\Http\Controllers\Fasilitator;

use App\Http\Controllers\Controller;
use App\Models\Skpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsUsiaController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->skpd) {
            $data = DB::table('ptt_biodata')
                    ->select(DB::raw('CASE
                                    WHEN TIMESTAMPDIFF(YEAR, thn_lahir, CURDATE()) < 20 THEN "<20"
                                    WHEN TIMESTAMPDIFF(YEAR, thn_lahir, CURDATE()) BETWEEN 20 AND 29 THEN "20-29"
                                    WHEN TIMESTAMPDIFF(YEAR, thn_lahir, CURDATE()) BETWEEN 30 AND 39 THEN "30-39"
                                    WHEN TIMESTAMPDIFF(YEAR, thn_lahir, CURDATE()) BETWEEN 40 AND 49 THEN "40-49"
                                    WHEN TIMESTAMPDIFF(YEAR, thn_lahir, CURDATE()) BETWEEN 50 AND 55 THEN "50-55"
                                    ELSE ">55"
                                END AS age_group'),
                            DB::raw('COUNT(*) AS total')
            )
                    ->where('aktif', 'Y')
                    ->groupBy('age_group')
                    ->get();

            $skpd = Skpd::where('id', auth()->user()->id_skpd)->first(['id', 'name']);

            foreach ($data as $value) {
                $items[] = $value->total;
                $labels[] = $value->age_group;
            }

            return view('fasilitator.statistik.usia', compact('skpd', 'items', 'labels'));
        } else {
            $id_skpd = explode(" - ", $request->skpd)[0];
            $data = DB::table('ptt_biodata')
                    ->select(DB::raw('CASE
                                    WHEN TIMESTAMPDIFF(YEAR, thn_lahir, CURDATE()) < 20 THEN "<20"
                                    WHEN TIMESTAMPDIFF(YEAR, thn_lahir, CURDATE()) BETWEEN 20 AND 29 THEN "20-29"
                                    WHEN TIMESTAMPDIFF(YEAR, thn_lahir, CURDATE()) BETWEEN 30 AND 39 THEN "30-39"
                                    WHEN TIMESTAMPDIFF(YEAR, thn_lahir, CURDATE()) BETWEEN 40 AND 49 THEN "40-49"
                                    WHEN TIMESTAMPDIFF(YEAR, thn_lahir, CURDATE()) BETWEEN 50 AND 55 THEN "50-55"
                                    ELSE ">55"
                                END AS age_group'),
                            DB::raw('COUNT(*) AS total')
                    )
                    ->where('id_skpd', 'like', $id_skpd . '%')
                    ->where('aktif', 'Y')
                    ->groupBy('age_group')
                    ->orderBy('age_group')
                    ->get();

            $skpd = Skpd::where('id', auth()->user()->id_skpd)->first(['id', 'name']);

            foreach ($data as $value) {
                $items[] = $value->total;
                $labels[] = $value->age_group;
            }

            return view('fasilitator.statistik.usia', compact('skpd', 'items', 'labels'));
        }
    }
}
