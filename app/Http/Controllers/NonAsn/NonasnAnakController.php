<?php

namespace App\Http\Controllers\NonAsn;

use App\Models\Anak;
use Hashids\Hashids;
use App\Models\SuamiIstri;
use App\Models\RefKelasBpjs;
use App\Models\RefStatusAnak;
use App\Models\RefPekerjaanAnak;
use App\Http\Requests\AnakRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class NonasnAnakController extends Controller
{
    public function index()
    {
        $id_ptt = auth()->user()->id_ptt;
        $hashId = $this->_hashId();

        $data = Anak::with(['statusAnak', 'pekerjaanAnak', 'orangTua'])
                ->whereId_ptt($id_ptt)->get();

        return view('nonasn.anak.index', compact('data', 'hashId'));
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

    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
    }

    public function create()
    {
        $submit = "Simpan";
        $hashId = $this->_hashId();

        $status_anak = RefStatusAnak::pluck('status_anak', 'status_anak_id');

        $pekerjaan = RefPekerjaanAnak::pluck('pekerjaan', 'pekerjaan_id');

        $ortu = SuamiIstri::whereId_ptt(auth()->user()->id_ptt)->whereAktif('Y')->first();

        $kelas = RefKelasBpjs::pluck('kelas', 'id');

        return view('nonasn.anak.create', compact('submit', 'status_anak', 'pekerjaan', 'ortu', 'kelas', 'hashId'));
    }

    public function store(AnakRequest $request)
    {
        try {
            list($tgl,$bln,$thn) = explode("/", $request->tgl_lahir);
            Anak::create([
                'id_ptt' => auth()->user()->id_ptt,
                'suami_istri_id' => $request->ortu,
                'nama' => $request->nama,
                'status_anak_id' => $request->status,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $thn . '-' . $bln . '-' . $tgl,
                'pekerjaan_anak_id' => $request->pekerjaan,
                'no_bpjs' => $request->no_bpjs,
                'kelas_id' => $request->kelas,
                'file_bpjs' => $request->hasFile('file_bpjs') ? $this->_uploadFile($request->file('file_bpjs')) : null
            ]);

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'input');

            return redirect()->route('nonasn.anak')->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function edit($id)
    {
        $submit = "Update";
        $hashId = $this->_hashId();

        $anak = Anak::findOrFail($hashId->decode($id)[0]);

        $status_anak = RefStatusAnak::pluck('status_anak', 'status_anak_id');

        $pekerjaan = RefPekerjaanAnak::pluck('pekerjaan', 'pekerjaan_id');

        $ortu = SuamiIstri::whereId_ptt(auth()->user()->id_ptt)->whereAktif('Y')->first();

        $kelas = RefKelasBpjs::pluck('kelas', 'id');

        return view('nonasn.anak.edit', compact('submit', 'anak', 'status_anak', 'pekerjaan', 'ortu', 'kelas', 'hashId'));
    }

    public function update(AnakRequest $request, $id)
    {
        try {
            $hashId = $this->_hashId();
            $id = $hashId->decode($id)[0];
            list($tgl,$bln,$thn) = explode("/", $request->tgl_lahir);

            $data = Anak::whereAnak_id($id)->first();
            if ($request->hasFile('file_bpjs')) {
                $file = $this->_uploadFile($request->file('file_bpjs'));
                if (Storage::disk('local')->exists('/upload_bpjs/' . $data->file_bpjs) && $data->file_bpjs != null) {
                    unlink(storage_path('app/upload_bpjs/' . $data->file_bpjs));
                }
            } else {
                $file = $data->file_bpjs;
            }

            if ($data) {
                $data->suami_istri_id = $request->ortu;
                $data->nama = $request->nama;
                $data->status_anak_id = $request->status;
                $data->tempat_lahir = $request->tempat_lahir;
                $data->tgl_lahir = $thn . '-' . $bln . '-' . $tgl;
                $data->pekerjaan_anak_id = $request->pekerjaan;
                $data->no_bpjs = $request->no_bpjs;
                $data->kelas_id = $request->kelas;
                $data->file_bpjs = $file;
                $data->save();
            }

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'update');

            return redirect()->route('nonasn.anak')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        try {
            $hashId = $this->_hashId();
            $data = Anak::find($hashId->decode($id)[0]);

            if ($data->file_bpjs) unlink(storage_path('app/upload_bpjs/' . $data->file_bpjs));
            $data->delete();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'hapus');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
