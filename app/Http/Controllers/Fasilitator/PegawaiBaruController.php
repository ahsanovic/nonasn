<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use App\Models\Biodata;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PegawaiBaruRequest;
use App\Models\DokumenPribadi;
use App\Models\Fasilitator\DownloadPegawai;

class PegawaiBaruController extends Controller
{
    public function index()
    {
        return view('fasilitator.pegawai_baru.index');
    }

    public function unor()
    {
        $skpd = Skpd::all();
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

    public function store(PegawaiBaruRequest $request)
    {
        $check_exist = Biodata::whereNiptt($request->niptt)->first(['niptt']);
        if ($check_exist) return back()->with(["type" => "error", "message" => "pegawai dengan nip " . $check_exist->niptt . " sudah ada!"]);

        DB::beginTransaction();
        try {
            list($id_skpd, $unit_kerja) = explode(" - ", $request->skpd);
            $pegawai = Biodata::create([
                'id_skpd' => $id_skpd,
                'jenis_ptt_id' => 1, // default is 'ptt-pk'
                'niptt' => $request->niptt,
                'nama' => $request->nama,
                'alamat' => '||||||',
                'password' => Hash::make('Nonasnjatim24')
            ]);

            // $last_id_ptt = $pegawai->id_ptt;

            // update table download
            DB::beginTransaction();
            try {
                if ($id_skpd > 3) {
                    $data = Skpd::whereId(substr($id_skpd,0,3))->first(['name']);
                    $skpd = $data->name;
                } else {
                    $skpd = $unit_kerja;
                }

                $data = Biodata::whereNiptt($request->niptt)->first(['id_ptt']);

                DownloadPegawai::create([
                    'id_ptt' => $data->id_ptt,
                    'niptt' => $request->niptt,
                    'nama' => $request->nama,
                    'jenis_ptt' => 'PTT-PK',
                    'alamat' => '||||||',
                    'id_skpd' => $id_skpd,
                    'unit_kerja' => $unit_kerja,
                    'skpd' => $skpd,
                    'aktif' => 'Y'
                ]);

                DB::commit();
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
                return back()->with(["type" => "error", "message" => "gagal update!"]);
            }

            // update table dokumen pribadi
            DB::beginTransaction();
            try {
                $data = Biodata::whereNiptt($request->niptt)->first(['id_ptt']);
                DokumenPribadi::create([
                    'id_ptt' => $data->id_ptt,
                ]);

                DB::commit();
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
                return back()->with(["type" => "error", "message" => "gagal update!"]);
            }

            DB::commit();
            return back()->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $err) {
            //throw $err;
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
