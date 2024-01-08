<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnitKerjaController extends Controller
{
    public function index()
    {
        return view('fasilitator.unit_kerja.index');
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
                'url' => url()->to('/fasilitator/unit-kerja') . '/' . $unor->id . '/edit',
                'icon' => url('/zTree/css/zTreeStyle/img/diy/1_close.png'),
                'open' => $unor->open
            ];
            array_push($tree, $array);
        }
        return response()->json($tree);
    }

    public function create()
    {
        $skpd = Skpd::get(['id', 'pId', 'name']);
        return view('fasilitator.unit_kerja.create', compact('skpd'));
    }

    public function store(Request $request)
    {
        // check duplicate entry
        $skpd = Skpd::whereId($request->id)->first(['id', 'pId', 'name']);
        if ($skpd) return back()->with(["type" => "error", "message" => "data id unit kerja {$request->id} sudah ada!"]);

        // validate form
        $request->validate([
            'pId' => 'required',
            'id' => 'required|numeric',
            'name' => 'required|max:100'
        ], [
            'pId.required' => 'id parent unit kerja harus dipilih',
            'id.required' => 'id unit kerja harus diisi',
            'id.numeric' => 'id unit kerja harus angka',
            'name.required' => 'nama unit kerja harus diisi',
            'name.max' => 'nama unit kerja maksimal 100 karakter',
        ]);
        
        $data = new Skpd();
        $data->id = $request->id;
        $data->pId = explode(" - ", $request->pId)[0];
        $data->name = $request->name;
        $data->save();

        logFasilitator(auth()->user()->username, auth()->user()->id_skpd, null, $request->segment(2), 'input');

        return redirect()->route('fasilitator.unit-kerja')->with(["type" => "success", "message" => "berhasil ditambahkan"]);
    }

    public function edit(Request $request)
    {
        $skpd = Skpd::select('id', 'pId', 'name')->whereId($request->segment(3))->first();
        $skpd_parent = Skpd::select('name')->whereId($skpd->pId)->first();
        return view('fasilitator.unit_kerja.edit', compact('skpd', 'skpd_parent'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100'
        ], [
            'name.required' => 'nama unit kerja harus diisi',
            'name.max' => 'nama unit kerja maksimal 100 karakter',
        ]);
        
        $data = Skpd::select('id', 'name')->whereId($request->idSkpd)->first();
        $data->name = $request->name;
        $data->save();

        logFasilitator(auth()->user()->username, auth()->user()->id_skpd, null, $request->segment(2), 'update');

        return redirect()->route('fasilitator.unit-kerja')->with(["type" => "success", "message" => "berhasil diubah"]);
    }

    public function destroy($id)
    {
        try {
            $data = Skpd::find($id);
            $data->delete();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, null, 'unit-kerja', 'hapus');

            return redirect()->route('fasilitator.unit-kerja')->with(["type" => "success", "message" => "berhasil dihapus"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
