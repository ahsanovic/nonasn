<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Fasilitator\DownloadPegawai;

class PegawaiNonAktifController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Biodata::select('id_ptt', 'id_skpd', 'nama', 'niptt', 'tempat_lahir', 'thn_lahir', 'jk' ,'foto')
                    ->whereAktif('N')
                    ->with(['skpd', 'pendidikan.jenjang', 'jabatan.refJabatan'])
                    ->when($request->nama, function($query) use ($request) {
                        $query->where('nama', 'like', '%' . $request->nama . '%')
                                ->orWhere('niptt', 'like', $request->nama . '%');
                    })
                    ->orderBy('id_ptt', 'asc')
                    ->paginate(12);

        return view('fasilitator.pegawai_nonaktif.index', compact('pegawai'));
    }

    public function autocomplete(Request $request)
    {
        $data = Biodata::whereAktif('N')
                ->where(function($query) use($request) {
                    $query->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('niptt', 'like', $request->search . '%');
                })
                ->limit(10)
                ->orderBy('nama', 'asc')
                ->get(['nama', 'niptt']);
        
        $res = [];
        foreach ($data as $value) {
            $res[] = [
                'label' => $value->nama
            ];
        }
        return response()->json($res);
    }

    public function aktivasi(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = Biodata::whereNiptt($request->niptt)->first(['id_ptt']);
            Biodata::whereNiptt($request->niptt)->update(['aktif' => 'Y', 'blokir' => 'N']);

            // update table download
            $update = DownloadPegawai::whereNiptt($request->niptt)->first();

            if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            $update->aktif = 'Y';
            $update->save();

            DB::commit();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $data->id_ptt, 'aktivasi', 'update');

            return redirect()->route('fasilitator.pegawai-nonaktif')->with(["type" => "success", "message" => "berhasil diaktifkan!"]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
