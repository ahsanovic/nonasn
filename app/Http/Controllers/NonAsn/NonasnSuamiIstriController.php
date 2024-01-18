<?php

namespace App\Http\Controllers\NonAsn;

use Hashids\Hashids;
use App\Models\SuamiIstri;
use App\Models\RefKelasBpjs;
use App\Models\RefPekerjaan;
use App\Models\RefSuamiIstri;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SuamiIstriRequest;

class NonasnSuamiIstriController extends Controller
{
    public function index()
    {
        $id_ptt = auth()->user()->id_ptt;
        $hashId = $this->_hashId();

        $data = SuamiIstri::with(['refSuamiIstri', 'pekerjaan'])
                ->whereId_ptt($id_ptt)->get();

        return view('nonasn.suami_istri.index', compact('data', 'hashId'));
    }

    public function viewFile($file)
    {
        try {
            $file = storage_path('app/upload_bpjs/' . $file);
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
        Storage::disk('local')->put('/upload_bpjs/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function create()
    {
        $submit = "Simpan";

        $status_suami_istri = RefSuamiIstri::pluck('status_suami_istri', 'status_suami_istri_id');

        $pekerjaan = RefPekerjaan::pluck('pekerjaan', 'pekerjaan_id');

        $kelas = RefKelasBpjs::pluck('kelas', 'id');

        return view('nonasn.suami_istri.create', compact('submit', 'status_suami_istri', 'pekerjaan', 'kelas'));
    }

    public function store(SuamiIstriRequest $request)
    {
        try {
            list($tgl,$bln,$thn) = explode("/", $request->tgl_lahir);
            SuamiIstri::create([
                'id_ptt' => auth()->user()->id_ptt,
                'nama_suami_istri' => $request->nama,
                'status_suami_istri_id' => $request->status,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $thn . '-' . $bln . '-' . $tgl,
                'pekerjaan_id' => $request->pekerjaan,
                'instansi' => $request->instansi,
                'no_bpjs' => $request->no_bpjs,
                'kelas_id' => $request->kelas,
                'file_bpjs' => $request->hasFile('file_bpjs') ? $this->_uploadFile($request->file('file_bpjs')) : null
            ]);

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'input');

            return redirect()
                ->route('nonasn.suami-istri')
                ->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
    }

    public function edit($id)
    {
        $submit = "Update";
        $hashId = $this->_hashId();
        $pasangan = SuamiIstri::findOrFail($hashId->decode($id)[0]);

        $status_suami_istri = RefSuamiIstri::pluck('status_suami_istri', 'status_suami_istri_id');
        
        $pekerjaan = RefPekerjaan::pluck('pekerjaan', 'pekerjaan_id');

        $kelas = RefKelasBpjs::pluck('kelas', 'id');

        return view('nonasn.suami_istri.edit', compact('submit', 'pasangan', 'status_suami_istri', 'pekerjaan', 'kelas', 'hashId'));
    }

    public function update(SuamiIstriRequest $request, $id)
    {
        try {
            list($tgl,$bln,$thn) = explode("/", $request->tgl_lahir);
            $hashId = $this->_hashId();

            $data = SuamiIstri::whereSuami_istri_id($hashId->decode($id)[0])->first();
            if ($request->hasFile('file_bpjs')) {
                $file = $this->_uploadFile($request->file('file_bpjs'));
                if (Storage::disk('local')->exists('/upload_bpjs/' . $data->file_bpjs) && $data->file_bpjs != null) {
                    unlink(storage_path('app/upload_bpjs/' . $data->file_bpjs));
                }
            } else {
                $file = $data->file_bpjs;
            }

            if ($data) {
                $data->nama_suami_istri = $request->nama;
                $data->status_suami_istri_id = $request->status;
                $data->tempat_lahir = $request->tempat_lahir;
                $data->tgl_lahir = $thn . '-' . $bln . '-' . $tgl;
                $data->pekerjaan_id = $request->pekerjaan;
                $data->instansi = $request->instansi;
                $data->no_bpjs = $request->no_bpjs;
                $data->kelas_id = $request->kelas;
                $data->file_bpjs = $file;
                $data->save();
            }

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'update');

            return redirect()
                ->route('nonasn.suami-istri')
                ->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function activate($id)
    {
        try {
            $hashId = $this->_hashId();
            $status_aktif = SuamiIstri::where('suami_istri_id', $hashId->decode($id)[0])->first(['aktif']);
            if ($status_aktif->aktif == 'Y') {
                SuamiIstri::select('aktif')->where('suami_istri_id', $hashId->decode($id)[0])->update(['aktif' => 'N']);
                SuamiIstri::select('aktif')->where('suami_istri_id', '!=', $hashId->decode($id)[0])->update(['aktif' => 'Y']);
            } else {
                SuamiIstri::select('aktif')->where('suami_istri_id', $hashId->decode($id)[0])->update(['aktif' => 'Y']);
                SuamiIstri::select('aktif')->where('suami_istri_id', '!=', $hashId->decode($id)[0])->update(['aktif' => 'N']);
            }

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'aktif');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil diupdate!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        try {
            $hashId = $this->_hashId();
            $data = SuamiIstri::find($hashId->decode($id)[0]);

            if ($data->file_bpjs) unlink(storage_path('app/upload_bpjs/' . $data->file_bpjs));
            $data->delete();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'hapus');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
