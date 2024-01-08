<?php

namespace App\Http\Controllers\Fasilitator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fasilitator\LogFasilitator;
use App\Http\Middleware\StripEmptyParamsFromQueryString;

class LogFasilitatorController extends Controller
{
    public function __construct()
    {
        $this->middleware(StripEmptyParamsFromQueryString::class)->only('index');
    }

    public function index(Request $request) {
        $per_page = $request->perPage ?? 10;
        if ($request->has('username') || $request->has('skpd') || $request->has('daterange')) {
            if ($request->has('perPage')) {
                $data = LogFasilitator::with(['skpd', 'biodata'])
                        ->when($request->username, function($query) use ($request) {
                            $query->where('username', $request->username);
                        })
                        ->when($request->skpd, function($query) use ($request) {
                            $query->where('id_skpd', 'like', explode(" - ", $request->skpd)[0] . '%');
                        })
                        ->when($request->daterange, function($query) use ($request) {
                            $start = explode(" - ", $request->daterange)[0];
                            [$start_date, $start_month, $start_year] = explode("/", $start);
                            $start_at = $start_year . '-' . $start_month . '-' . $start_date;
    
                            $end = explode(" - ", $request->daterange)[1];
                            [$start_date, $start_month, $start_year] = explode("/", $end);
                            $end_at = $start_year . '-' . $start_month . '-' . $start_date;
    
                            $query->whereBetween('tgl', [$start_at, $end_at]);
                        })
                        ->orderByDesc('id')
                        ->paginate($request->perPage)
                        ->appends('perPage', $request->perPage);
            } else {
                $data = LogFasilitator::with(['skpd', 'biodata'])
                        ->when($request->username, function($query) use ($request) {
                            $query->where('username', $request->username);
                        })
                        ->when($request->skpd, function($query) use ($request) {
                            $query->where('id_skpd', 'like', explode(" - ", $request->skpd)[0] . '%');
                        })
                        ->when($request->daterange, function($query) use ($request) {
                            $start = explode(" - ", $request->daterange)[0];
                            [$start_date, $start_month, $start_year] = explode("/", $start);
                            $start_at = $start_year . '-' . $start_month . '-' . $start_date;
    
                            $end = explode(" - ", $request->daterange)[1];
                            [$start_date, $start_month, $start_year] = explode("/", $end);
                            $end_at = $start_year . '-' . $start_month . '-' . $start_date;
    
                            $query->whereBetween('tgl', [$start_at, $end_at]);
                        })
                        ->orderByDesc('id')
                        ->paginate($per_page);
            }
        } else {
            $data = LogFasilitator::with(['skpd', 'biodata'])
                    ->orderByDesc('id')
                    ->paginate($per_page);
        }
        
        return view('fasilitator.log.fasilitator.index', compact('data', 'per_page'));
    }
}
