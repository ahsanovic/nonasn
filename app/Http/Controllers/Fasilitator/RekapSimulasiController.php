<?php

namespace App\Http\Controllers\Fasilitator;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\HasilSimulasiCpns;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Middleware\StripEmptyParamsFromQueryString;

class RekapSimulasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(StripEmptyParamsFromQueryString::class)->only('cpns', 'pppk');
    }
    
    public function cpns(Request $request)
    {
        $per_page = $request->perPage ?? 10;
        if ($request->has('niptt') || $request->has('skpd') || $request->has('daterange')) {
            if ($request->has('perPage')) {
                $data = HasilSimulasiCpns::with(['biodata', 'biodata.skpd'])
                        ->when($request->niptt, function($query) use ($request) {
                            $query->whereHas('biodata', function($q) use ($request) {
                                $q->where('niptt', $request->niptt)
                                    ->orWhere('nama', 'like', '%' . $request->niptt . '%');
                            });
                        })
                        ->when($request->skpd, function($query) use ($request) {
                            $query->whereHas('biodata.skpd', function($q) use ($request) {
                                $q->where('id_skpd', 'like', explode(" - ", $request->skpd)[0] . '%');
                            });
                        })
                        ->when($request->daterange, function($query) use ($request) {
                            $start = explode(" - ", $request->daterange)[0];    
                            $end = explode(" - ", $request->daterange)[1];    
                            $query->whereBetween('created_at', [
                                Carbon::createFromFormat('d/m/Y', $start)->startOfDay(),
                                Carbon::createFromFormat('d/m/Y', $end)->endOfDay(),
                            ]);
                        })
                        ->orderByDesc('id')
                        ->paginate($request->perPage)
                        ->appends('perPage', $request->perPage);
            } else {
                $data = HasilSimulasiCpns::with(['biodata', 'biodata.skpd'])
                        ->when($request->niptt, function($query) use ($request) {
                            $query->whereHas('biodata', function($q) use ($request) {
                                $q->where('niptt', $request->niptt)
                                    ->orWhere('nama', 'like', '%' . $request->niptt . '%');
                            });
                        })
                        ->when($request->skpd, function($query) use ($request) {
                            $query->whereHas('biodata.skpd', function($q) use ($request) {
                                $q->where('id_skpd', 'like', explode(" - ", $request->skpd)[0] . '%');
                            });
                        })
                        ->when($request->daterange, function($query) use ($request) {
                            $start = explode(" - ", $request->daterange)[0];
                            $end = explode(" - ", $request->daterange)[1];
                            $query->whereBetween('created_at', [
                                Carbon::createFromFormat('d/m/Y', $start)->startOfDay(),
                                Carbon::createFromFormat('d/m/Y', $end)->endOfDay(),
                            ]);
                        })
                        ->orderByDesc('id')
                        ->paginate($per_page);
            }
        } else {
            $data = HasilSimulasiCpns::with(['biodata', 'biodata.skpd'])
                    ->orderByDesc('id')
                    ->paginate($per_page);
        }

        return view('fasilitator.rekap_simulasi.cpns.index', compact('data', 'per_page'));
    }

    public function downloadExcelSimulasiCpns(Request $request)
    {
        $data = HasilSimulasiCpns::with(['biodata', 'biodata.skpd'])
            ->when($request->niptt, function ($query) use ($request) {
                $query->whereHas('biodata', function ($q) use ($request) {
                    $q->where('niptt', $request->niptt)
                        ->orWhere('nama', 'like', '%' . $request->niptt . '%');
                });
            })
            ->when($request->skpd, function ($query) use ($request) {
                $query->whereHas('biodata.skpd', function ($q) use ($request) {
                    $q->where('id_skpd', 'like', explode(" - ", $request->skpd)[0] . '%');
                });
            })
            ->when($request->daterange, function ($query) use ($request) {
                $start = explode(" - ", $request->daterange)[0];
                $end = explode(" - ", $request->daterange)[1];
                $query->whereBetween('created_at', [
                    Carbon::createFromFormat('d/m/Y', $start)->startOfDay(),
                    Carbon::createFromFormat('d/m/Y', $end)->endOfDay(),
                ]);
            })
            ->orderByDesc('id')
            ->get();

        // Transform data if needed
        $exportData = $data->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Nama' => $item->biodata->nama ?? '-',
                'NIPTT' => $item->biodata->niptt ?? '-',
                'Unit Kerja' => $item->biodata->skpd->name ?? '-',
                'TWK' => $item->nilai_twk,
                'TIU' => $item->nilai_tiu,
                'TKP' => $item->nilai_tkp,
                'Total' => $item->nilai_total,
                'Tanggal Simulasi' => $item->created_at->format('d/m/Y H:i:s'),
            ];
        });

        // Generate and download Excel
        return (new FastExcel($exportData))->download('hasil_simulasi_cpns.xlsx');
    }
}