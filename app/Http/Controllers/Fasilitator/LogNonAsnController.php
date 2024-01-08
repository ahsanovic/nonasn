<?php

namespace App\Http\Controllers\Fasilitator;

use App\Http\Controllers\Controller;
use App\Http\Middleware\StripEmptyParamsFromQueryString;
use App\Models\NonAsn\LogNonAsn;
use Illuminate\Http\Request;

class LogNonAsnController extends Controller
{
    public function __construct()
    {
        $this->middleware(StripEmptyParamsFromQueryString::class)->only('index');
    }

    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 10;
        if ($request->has('daterange') || $request->has('nama') || $request->has('skpd')) {
            if ($request->has('perPage')) {
                $data = LogNonAsn::with('biodata')
                        ->when($request->daterange, function($query) use ($request) {
                            $start = explode(" - ", $request->daterange)[0];
                            [$start_date, $start_month, $start_year] = explode("/", $start);
                            $start_at = $start_year . '-' . $start_month . '-' . $start_date;
    
                            $end = explode(" - ", $request->daterange)[1];
                            [$start_date, $start_month, $start_year] = explode("/", $end);
                            $end_at = $start_year . '-' . $start_month . '-' . $start_date;
    
                            $query->whereBetween('tgl', [$start_at, $end_at]);
                        })
                        ->whereHas('biodata', function($query) use ($request) {
                            $query->where('nama', 'like' , '%' . $request->nama . '%')
                                ->orWhere('niptt', 'like', '%' . $request->nama . '%');
                        })
                        ->whereHas('biodata', function($query) use ($request) {
                            $query->where('id_skpd', 'like' , explode(" - ", $request->skpd)[0] . '%');
                        })
                        ->orderByDesc('log_id')
                        ->paginate($request->perPage)
                        ->appends('perPage', $request->perPage);
            } else {
                $data = LogNonAsn::with('biodata')
                        ->when($request->daterange, function($query) use ($request) {
                            $start = explode(" - ", $request->daterange)[0];
                            [$start_date, $start_month, $start_year] = explode("/", $start);
                            $start_at = $start_year . '-' . $start_month . '-' . $start_date;
    
                            $end = explode(" - ", $request->daterange)[1];
                            [$start_date, $start_month, $start_year] = explode("/", $end);
                            $end_at = $start_year . '-' . $start_month . '-' . $start_date;
    
                            $query->whereBetween('tgl', [$start_at, $end_at]);
                        })
                        ->whereHas('biodata', function($query) use ($request) {
                            $query->where('nama', 'like' , '%' . $request->nama . '%')
                                ->orWhere('niptt', 'like', '%' . $request->nama . '%');
                        })
                        ->whereHas('biodata', function($query) use ($request) {
                            $query->where('id_skpd', 'like' , explode(" - ", $request->skpd)[0] . '%');
                        })
                        ->orderByDesc('log_id')
                        ->paginate($per_page);
            }
        } else {
            $data = LogNonAsn::with('biodata')
                    ->orderByDesc('log_id')
                    ->paginate($per_page);
        }

        return view('fasilitator.log.nonasn.index', compact('data', 'per_page'));
    }
}
