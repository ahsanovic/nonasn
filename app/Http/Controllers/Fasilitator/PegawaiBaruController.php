<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use App\Models\Biodata;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PegawaiBaruRequest;

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
        try {
            list($id_skpd) = explode(" - ", $request->skpd);
            Biodata::create([
                'id_skpd' => $id_skpd,
                'niptt' => $request->niptt,
                'nama' => $request->nama,
                'password' => Hash::make('nonasnjatim')
            ]);
            return back()->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $err) {
            // throw $err;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
