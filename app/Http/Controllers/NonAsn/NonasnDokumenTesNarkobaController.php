<?php

namespace App\Http\Controllers\NonAsn;

use Hashids\Hashids;
use Illuminate\Http\Request;
use App\Models\DokumenTesNarkoba;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Fasilitator\DownloadPegawai;
use App\Http\Requests\DokumenTesNarkobaRequest;

class NonasnDokumenTesNarkobaController extends Controller
{
    public function viewFile($file)
    {
        try {
            $file = storage_path('app/upload_dok_tes_narkoba/' . $file);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
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
        Storage::disk('local')->put('/upload_dok_tes_narkoba/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function index()
    {
        $id_ptt = auth()->user()->id_ptt;
        $hashId = $this->_hashId();

        $data = DokumenTesNarkoba::whereId_ptt($id_ptt)
                    ->orderByDesc('tahun')
                    ->get();

        return view('nonasn.dok_tes_narkoba.index', compact('data', 'hashId'));
    }

    public function create()
    {
        $submit = "Simpan";
        return view('nonasn.dok_tes_narkoba.create', compact('submit'));
    }

    public function store(DokumenTesNarkobaRequest $request)
    {
        try {
            DokumenTesNarkoba::create([
                'id_ptt' => auth()->user()->id_ptt,
                'tahun' => $request->tahun,
                'nomor_surat' => $request->nomor_surat,
                'tgl_surat' => $request->tgl_surat,
                'dokter_pemeriksa' => $request->dokter_pemeriksa,
                'instansi' => $request->instansi,
                'file' => $request->hasFile('file') ? $this->_uploadFile($request->file('file')) : null
            ]);

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'input');

            return redirect()->route('nonasn.dok-narkoba')->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function edit($id)
    {
        $submit = "Update";
        $hashId = $this->_hashId();

        $data = DokumenTesNarkoba::findOrFail($hashId->decode($id)[0]);
        return view('nonasn.dok_tes_narkoba.edit', compact('data', 'submit', 'hashId'));
    }

    public function update(DokumenTesNarkobaRequest $request, $id)
    {
        try {
            $hashId = $this->_hashId();

            $data = DokumenTesNarkoba::whereId($hashId->decode($id)[0])->first();
            if ($request->hasFile('file')) {
                $file = $this->_uploadFile($request->file('file'));
                if (Storage::disk('local')->exists('/upload_dok_tes_narkoba/' . $data->file) && $data->file != null) {
                    unlink(storage_path('app/upload_dok_tes_narkoba/' . $data->file));
                }
            } else {
                $file = $data->file;
            }

            if ($data) {
                $data->tahun = $request->tahun;
                $data->nomor_surat = $request->nomor_surat;
                $data->tgl_surat = $request->tgl_surat;
                $data->dokter_pemeriksa = $request->dokter_pemeriksa;
                $data->instansi = $request->instansi;
                $data->file = $file;
                $data->save();
            }

            if ($data->aktif == 'Y') {
                DB::beginTransaction();
                try {
                    // update table download
                    $update = DownloadPegawai::whereId_ptt($data->id_ptt)->first();        
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->tes_narkoba = 'Sudah';
                    $update->tahun_tes_narkoba = $request->tahun;
                    $update->save();
        
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return back()->with(["type" => "error", "message" => "gagal update!"]);
                }
            }

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'update');

            return redirect()->route('nonasn.dok-narkoba')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function activate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $hashId = $this->_hashId();
            $id = $hashId->decode($id)[0];

            $status_aktif = DokumenTesNarkoba::where('id', $id)->first(['aktif']);
            if ($status_aktif->aktif == 'Y') {
                DokumenTesNarkoba::where('id', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'N']);

                DokumenTesNarkoba::where('id', '!=', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'Y']);
            } else {
                DokumenTesNarkoba::where('id', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'Y']);

                DokumenTesNarkoba::where('id', '!=', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'N']);
            }

            // update table download
            $update = DownloadPegawai::whereId_ptt(auth()->user()->id_ptt)->first();
            if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            $update->tes_narkoba = 'Sudah';
            $update->tahun_tes_narkoba = $request->tahun;
            $update->save();

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'aktif');

            return back()->with(["type" => "success", "message" => "berhasil diupdate!"]);
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
            $data = DokumenTesNarkoba::find($hashId->decode($id)[0]);

            if ($data->aktif == 'Y') {
                DB::beginTransaction();
                try {
                    // update table download
                    $update = DownloadPegawai::whereId_ptt($data->id_ptt)->first();        
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->tes_narkoba = null;
                    $update->tahun_tes_narkoba = null;
                    $update->save();
        
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return back()->with(["type" => "error", "message" => "gagal menghapus!"]);
                }
            }

            if ($data->file) unlink(storage_path('app/upload_dok_tes_narkoba/' . $data->file));
            $data->delete();

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'hapus');

            return back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}