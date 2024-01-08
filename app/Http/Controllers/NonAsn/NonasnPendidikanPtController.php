<?php

namespace App\Http\Controllers\NonAsn;

use App\Models\Skpd;
use Hashids\Hashids;
use App\Models\Biodata;
use App\Models\Jenjang;
use App\Models\Pendidikan;
use Illuminate\Http\Request;
use App\Models\RefAkreditasi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PendidikanPtRequest;
use App\Models\Fasilitator\DownloadPegawai;

class NonasnPendidikanPtController extends Controller
{
    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
    }

    private function _hashIdJenjang()
    {
        return new Hashids(env('SECRET_SALT_KEY'),5);
    }
    
    public function index(Request $request)
    {
        $hashIdJenjang = $this->_hashIdJenjang();
        $data = Pendidikan::with(['jenjang'])
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->get();

        return view('nonasn.pendidikan.index', compact('data', 'hashIdJenjang'));
    }

    public function viewFileIjazah($file)
    {
        try {
            $file = storage_path('app/upload_ijazah/' . $file);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function viewFileTranskrip($file)
    {
        try {
            $file = storage_path('app/upload_transkrip/' . $file);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    private function _uploadFileIjazah($file)
    {
        $filenameWithExt = $file->getClientOriginalName();
        // Get only filename without extension
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get extension
        $extension = $file->getClientOriginalExtension();
        // Give a new name
        $time = date('YmdHis', time());
        $filenameToStore = $time . '-' . uniqid() . '-' . preg_replace("/\s+/", "_", $filename) . '.' . $extension;
        // Upload file
        Storage::disk('local')->put('/upload_ijazah/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    private function _uploadFileTranskrip($file)
    {
        $filenameWithExt = $file->getClientOriginalName();
        // Get only filename without extension
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get extension
        $extension = $file->getClientOriginalExtension();
        // Give a new name
        $time = date('YmdHis', time());
        $filenameToStore = $time . '-' . uniqid() . '-' . preg_replace("/\s+/", "_", $filename) . '.' . $extension;
        // Upload file
        Storage::disk('local')->put('/upload_transkrip/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function create()
    {
        $submit = "Simpan";

        $jenjang = Jenjang::where('id_jenjang', '>', 3)->pluck('nama_jenjang', 'id_jenjang');

        $akreditasi = RefAkreditasi::pluck('akreditasi', 'id');

        return view('nonasn.pendidikan.create_pt', compact('submit', 'jenjang', 'akreditasi'));
    }

    public function store(PendidikanPtRequest $request)
    {
        try {
            Pendidikan::create([
                'id_ptt' => auth()->user()->id_ptt,
                'id_jenjang' => $request->jenjang_pt,
                'nama_pt' => $request->nama_pt,
                'fakultas_pt' => $request->fakultas_pt,
                'jurusan_prodi_pt' => $request->jurusan_prodi_pt,
                'akreditasi_pt' => $request->akreditasi_pt,
                'thn_lulus_pt' => $request->thn_lulus_pt,
                'no_ijazah_pt' => $request->no_ijazah_pt,
                'tgl_ijazah_pt' => $request->tgl_ijazah_pt,
                'ipk_pt' => $request->ipk_pt,
                'file_ijazah_pt' => $request->hasFile('file_ijazah_pt') ? $this->_uploadFileIjazah($request->file('file_ijazah_pt')) : null,
                'file_nilai_pt' => $request->hasFile('file_nilai_pt') ? $this->_uploadFileTranskrip($request->file('file_nilai_pt')) : null
            ]);

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'input');

            return redirect()->route('nonasn.pendidikan-sma')->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function edit($id)
    {
        $submit = "Update";
        $hashId = $this->_hashId();

        $data = Pendidikan::findOrFail($hashId->decode($id)[0]);

        $jenjang = Jenjang::where('id_jenjang', '>', 3)->pluck('nama_jenjang', 'id_jenjang');

        $akreditasi = RefAkreditasi::pluck('akreditasi', 'id');

        return view('nonasn.pendidikan.edit_pt', compact('submit', 'data', 'jenjang', 'akreditasi', 'hashId'));
    }

    public function update(PendidikanPtRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $hashId = $this->_hashId();
            $data = Pendidikan::whereId_ptt_pendidikan($hashId->decode($id)[0])->first();
            
            if ($request->hasFile('file_ijazah_pt')) {
                $file_ijazah_pt = $this->_uploadFileIjazah($request->file('file_ijazah_pt'));
                if (Storage::disk('local')->exists('/upload_ijazah/' . $data->file) && $data->file_ijazah_pt != null) {
                    unlink(storage_path('app/upload_ijazah/' . $data->file_ijazah_pt));
                }
            } else {
                $file_ijazah_pt = $data->file_ijazah_pt;
            }

            if ($request->hasFile('file_nilai_pt')) {
                $file_nilai_pt = $this->_uploadFileTranskrip($request->file('file_nilai_pt'));
                if (Storage::disk('local')->exists('/upload_transkrip/' . $data->file) && $data->file_nilai_pt != null) {
                    unlink(storage_path('app/upload_transkrip/' . $data->file_nilai_pt));
                }
            } else {
                $file_nilai_pt = $data->file_nilai_pt;
            }

            if ($data) {
                $data->id_jenjang = $request->jenjang_pt;
                $data->nama_pt = $request->nama_pt;
                $data->fakultas_pt = $request->fakultas_pt;
                $data->jurusan_prodi_pt = $request->jurusan_prodi_pt;
                $data->akreditasi_pt = $request->akreditasi_pt;
                $data->thn_lulus_pt = $request->thn_lulus_pt;
                $data->no_ijazah_pt = $request->no_ijazah_pt;
                $data->tgl_ijazah_pt = $request->tgl_ijazah_pt;
                $data->ipk_pt = $request->ipk_pt;
                $data->file_ijazah_pt = $file_ijazah_pt;
                $data->file_nilai_pt = $file_nilai_pt;
                $data->save();
            }

            if ($data->aktif == 'Y') {
                DB::beginTransaction();
                try {
                    // update table download
                    $ref_jenjang = Jenjang::whereId_jenjang($data->id_jenjang)->first(['nama_jenjang']);
                    $update = DownloadPegawai::whereId_ptt($data->id_ptt)->first();
        
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->jenjang = $ref_jenjang->nama_jenjang;
                    $update->nama_sekolah = $request->nama_pt;
                    $update->jurusan = $request->jurusan_prodi_pt;
                    $update->akreditasi = $request->akreditasi_pt;
                    $update->thn_lulus = $request->thn_lulus_pt;
                    $update->save();
        
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return back()->with(["type" => "error", "message" => "gagal update!"]);
                }
            }

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'update');

            return redirect()->route('nonasn.pendidikan-sma')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function activate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $hashId = $this->_hashId();
            $id = $hashId->decode($id)[0];

            $status_aktif = Pendidikan::where('id_ptt_pendidikan', $id)->first(['aktif']);
            if ($status_aktif->aktif == 'Y') {
                Pendidikan::where('id_ptt_pendidikan', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'N']);

                Pendidikan::where('id_ptt_pendidikan', '!=', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'Y']);
            } else {
                Pendidikan::where('id_ptt_pendidikan', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'Y']);
                
                Pendidikan::where('id_ptt_pendidikan', '!=', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'N']);
            }

            // update table download
            $ref_jenjang = Jenjang::whereId_jenjang($this->_hashIdJenjang()->decode($request->jenjang_pt)[0])->first(['nama_jenjang']);
            $update = DownloadPegawai::whereId_ptt(auth()->user()->id_ptt)->first();

            if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            $update->jenjang = $ref_jenjang->nama_jenjang;
            $update->nama_sekolah = $request->nama_pt;
            $update->jurusan = $request->jurusan_prodi_pt;
            $update->akreditasi = $request->akreditasi_pt;
            $update->thn_lulus = $request->thn_lulus_pt;
            $update->save();

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'aktif');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil diupdate!"]);
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
            $data = Pendidikan::find($hashId->decode($id)[0]);

            if ($data->aktif == 'Y') {
                DB::beginTransaction();
                try {
                    // update table download
                    $update = DownloadPegawai::whereId_ptt($data->id_ptt)->first();        
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->jenjang = null;
                    $update->nama_sekolah = null;
                    $update->jurusan = null;
                    $update->akreditasi = null;
                    $update->thn_lulus = null;
                    $update->save();
        
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return back()->with(["type" => "error", "message" => "gagal menghapus!"]);
                }
            }

            if ($data->file_ijazah_pt) unlink(storage_path('app/upload_ijazah/' . $data->file_ijazah_pt));
            if ($data->file_nilai_pt) unlink(storage_path('app/upload_transkrip/' . $data->file_nilai_pt));
            $data->delete();

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'hapus');

            return back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
