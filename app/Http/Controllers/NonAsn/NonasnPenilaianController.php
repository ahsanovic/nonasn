<?php

namespace App\Http\Controllers\NonAsn;

use Hashids\Hashids;
use App\Models\Penilaian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\PenilaianRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Fasilitator\DownloadPegawai;

class NonasnPenilaianController extends Controller
{
    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
    }
    
    public function index()
    {
        $hashId = $this->_hashId();
        $data = Penilaian::whereId_ptt(auth()->user()->id_ptt)->orderByDesc('tahun')->get();

        return view('nonasn.penilaian.index', compact('data', 'hashId'));
    }

    public function create()
    {
        $submit = "Simpan";

        return view('nonasn.penilaian.create', compact('submit'));
    }

    public function viewFile($file)
    {
        try {
            $file = storage_path('app/upload_penilaian/' . $file);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    private function _uploadFile($file)
    {
        $filenameWithExt = $file->getClientOriginalName();
        // Get only filename without extension
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get extension
        $extension = $file->getClientOriginalExtension();
        // Give a new name
        $time = date('YmdHis', time());
        $filenameToStore = $time . '-' . uniqid() . '.' . $extension;
        // Upload file
        Storage::disk('local')->put('/upload_penilaian/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function store(PenilaianRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = Penilaian::whereId_ptt(auth()->user()->id_ptt)->whereTahun($request->tahun)->first(['tahun']);
            if ($data) return back()->with(["type" => "error", "message" => "data tahun " . $request->tahun . " sudah ada!"]);

            Penilaian::create([
                'id_ptt' => auth()->user()->id_ptt,
                'tahun' => $request->tahun,
                'nilai' => $request->nilai,
                'rekomendasi' => $request->rekomendasi,
                'file' => $request->hasFile('file') ? $this->_uploadFile($request->file('file')) : null
            ]);

            // update table download
            $update = DownloadPegawai::whereId_ptt(auth()->user()->id_ptt)->first();        
            if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            $update->tahun_penilaian = $request->tahun;
            $update->rekomendasi = $request->rekomendasi;
            $update->save();

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'input');

            return redirect()->route('nonasn.penilaian')->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function edit($id)
    {
        $submit = "Update";
        $hashId = $this->_hashId();
        $data = Penilaian::findOrFail($hashId->decode($id)[0]);

        return view('nonasn.penilaian.edit', compact('submit', 'data', 'hashId'));
    }

    public function update(PenilaianRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $hashId = $this->_hashId();
            $data = Penilaian::whereId_ptt_penilaian($hashId->decode($id)[0])->first();
            if ($request->hasFile('file')) {
                $file = $this->_uploadFile($request->file('file'));
                if (Storage::disk('local')->exists('/upload_penilaian/' . $data->file) && $data->file != null) {
                    unlink(storage_path('app/upload_penilaian/' . $data->file));
                }
            } else {
                $file = $data->file;
            }

            if ($data) {
                $data->tahun = $request->tahun;
                $data->nilai = $request->nilai;
                $data->rekomendasi = $request->rekomendasi;
                $data->file = $file;
                $data->save();
            }

            $row = Penilaian::whereId_ptt(auth()->user()->id_ptt)->orderByDesc('tahun')->first(['tahun']);
            if ($row->tahun == $request->tahun) {
                DB::beginTransaction();
                try {
                    // update table download
                    $update = DownloadPegawai::whereId_ptt(auth()->user()->id_ptt)->first();
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->tahun_penilaian = $request->tahun;
                    $update->rekomendasi = $request->rekomendasi;
                    $update->save();
        
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return back()->with(["type" => "error", "message" => "gagal update!"]);
                }
            }

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'update');

            return redirect()->route('nonasn.penilaian')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $hashId = $this->_hashId();
            $data = Penilaian::find($hashId->decode($id)[0]);

            if ($data->file) unlink(storage_path('app/upload_penilaian/' . $data->file));
            $data->delete();

            $count_data = Penilaian::whereId_ptt(auth()->user()->id_ptt)->count();
            $row = Penilaian::select('tahun', 'rekomendasi')->whereId_ptt(auth()->user()->id_ptt)->orderByDesc('tahun')->first();
            if ($count_data == 0) {
                DB::beginTransaction();
                try {
                    // update table download
                    $update = DownloadPegawai::whereId_ptt(auth()->user()->id_ptt)->first();
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->tahun_penilaian = null;
                    $update->rekomendasi = null;
                    $update->save();
        
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return back()->with(["type" => "error", "message" => "gagal menghapus!"]);
                }
            } else {
                DB::beginTransaction();
                try {
                    // update table download
                    $update = DownloadPegawai::whereId_ptt(auth()->user()->id_ptt)->first();
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->tahun_penilaian = $row->tahun;
                    $update->rekomendasi = $row->rekomendasi;
                    $update->save();
        
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return back()->with(["type" => "error", "message" => "gagal menghapus!"]);
                }
            }

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'hapus');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
