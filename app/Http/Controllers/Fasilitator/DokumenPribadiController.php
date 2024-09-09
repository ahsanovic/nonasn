<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Hashids\Hashids;
use App\Models\Biodata;
use App\Models\RefDokumen;
use Illuminate\Http\Request;
use App\Models\DokumenPribadi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DokumenPribadiController extends Controller
{
    protected $hashidSkpd;
    protected $hashidPegawai;
    protected $hashid;

    public function __construct()
    {
        $this->hashidSkpd = $this->_hashIdSkpd();
        $this->hashidPegawai = $this->_hashIdPegawai();
        $this->hashid = $this->_hashId();
    }

    private function _hashIdSkpd()
    {
        return new Hashids(env('SECRET_SALT_KEY'),15);
    }

    private function _hashIdPegawai()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
    }

    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
    }
    
    public function index(Request $request)
    {
        $hashidSkpd = $this->hashidSkpd;
        $hashidPegawai = $this->hashidPegawai;
        $hashid = $this->hashid;

        $idSkpd = $hashidSkpd->decode($request->segment(3))[0] ?? '';
        if (!$idSkpd) return back()->with(["type" => "error", "message" => "forbidden!"]);

        // check scope of id skpd
        if (!in_array($idSkpd, getScopeIdSkpd())) return back()->with(["type" => "error", "message" => "forbidden!"]);

        $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

        $refDokumen = RefDokumen::all();

        $data = DokumenPribadi::whereId_ptt($idPegawai)->first();

        $pegawai = Biodata::select('id_ptt', 'jenis_ptt_id', 'id_skpd', 'nama', 'foto')
                    ->whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first();
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::select('id', 'name')->whereId($idSkpd)->first();
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.dok_pribadi.index', compact(
            'data',
            'pegawai',
            'skpd',
            'refDokumen',
            'hashid',
            'hashidSkpd',
            'hashidPegawai'
        ));
    }

    public function viewFile($file)
    {
        try {
            $file = storage_path('app/upload_dok_pribadi/' . $file);
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
        Storage::disk('local')->put('/upload_dok_pribadi/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function edit(Request $request)
    {
        $hashidSkpd = $this->hashidSkpd;
        $hashidPegawai = $this->hashidPegawai;
        $hashid = $this->hashid;

        $idSkpd = $hashidSkpd->decode($request->segment(3))[0] ?? '';
        if (!$idSkpd) return back()->with(["type" => "error", "message" => "forbidden!"]);

        // check scope of id skpd
        if (!in_array($idSkpd, getScopeIdSkpd())) return back()->with(["type" => "error", "message" => "forbidden!"]);

        $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

        $data = DokumenPribadi::whereId_ptt($idPegawai)->first();
        if (!$data) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $pegawai = Biodata::select('id_ptt', 'jenis_ptt_id', 'id_skpd', 'nama', 'foto')
                    ->whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first();
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::select('id', 'name')->whereId($idSkpd)->first();
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.dok_pribadi.edit', compact(
            'data',
            'pegawai',
            'skpd',
            'hashidSkpd',
            'hashidPegawai',
            'hashid'
        ));
    }

    public function update(Request $request)
    {
        $hashid = $this->hashid;
        $id = $hashid->decode($request->idDokumen)[0] ?? '';

        if ($request->field == 'file_ktp') {
            $request->validate([
                'file_ktp' => ['required','file','mimes:jpg,jpeg,png','max:512']
            ], [
                'file_ktp.required' => 'dokumen harus diupload',
                'file_ktp.mimes' => 'dokumen harus berformat jpg/png',
                'file_ktp.max' => 'dokumen maksimal berukuran 512 kilobytes',
            ]);

            $data = DokumenPribadi::whereId($id)->first();
            if ($request->hasFile('file_ktp')) {
                $file = $this->_uploadFile($request->file('file_ktp'));
                if (Storage::disk('local')->exists('/upload_dok_pribadi/' . $data->file_ktp) && $data->file_ktp != null) {
                    unlink(storage_path('app/upload_dok_pribadi/' . $data->file_ktp));
                }
            }
            
            if ($data) {
                $data->file_ktp = $file;
                $data->updated_at_file_ktp = date('Y-m-d H:i:s');
                $data->save();
            }
        }

        if ($request->field == 'file_bpjs') {
            $request->validate([
                'file_bpjs' => ['required','file','mimes:jpg,jpeg,png','max:512'],
                'file_bpjs.mimes' => 'dokumen harus berformat jpg/png',
                'file_bpjs.max' => 'dokumen maksimal berukuran 512 kilobytes',
            ], [
                'file_bpjs.required' => 'dokumen harus diupload',
                'file_bpjs.mimes' => 'dokumen harus berformat jpg/png',
                'file_bpjs.max' => 'dokumen maksimal berukuran 512 kilobytes',
            ]);

            $data = DokumenPribadi::whereId($id)->first();
            if ($request->hasFile('file_bpjs')) {
                $file = $this->_uploadFile($request->file('file_bpjs'));
                if (Storage::disk('local')->exists('/upload_dok_pribadi/' . $data->file_bpjs) && $data->file_bpjs != null) {
                    unlink(storage_path('app/upload_dok_pribadi/' . $data->file_bpjs));
                }
            }

            if ($data) {
                $data->file_bpjs = $file;
                $data->updated_at_file_bpjs = date('Y-m-d H:i:s');
                $data->save();
            }
        }

        if ($request->field == 'file_bpjs_naker') {
            $request->validate([
                'file_bpjs_naker' => ['required','file','mimes:jpg,jpeg,png','max:512']
            ],  [
                'file_bpjs_naker.required' => 'dokumen harus diupload',
                'file_bpjs_naker.mimes' => 'dokumen harus berformat jpg/png',
                'file_bpjs_naker.max' => 'dokumen maksimal berukuran 512 kilobytes',
            ]);

            $data = DokumenPribadi::whereId($id)->first();
            if ($request->hasFile('file_bpjs_naker')) {
                $file = $this->_uploadFile($request->file('file_bpjs_naker'));
                if (Storage::disk('local')->exists('/upload_dok_pribadi/' . $data->file_bpjs_naker) && $data->file_bpjs_naker != null) {
                    unlink(storage_path('app/upload_dok_pribadi/' . $data->file_bpjs_naker));
                }
            }

            if ($data) {
                $data->file_bpjs_naker = $file;
                $data->updated_at_file_bpjs_naker = date('Y-m-d H:i:s');
                $data->save();
            }
        }

        logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $this->hashidPegawai->decode($request->segment(5))[0], $request->segment(4), 'update');

        return redirect()
            ->route('fasilitator.dok-pribadi', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
            ->with(["type" => "success", "message" => "berhasil diubah!"]);
    }

    public function destroy(Request $request, $id)
    {
        try {
            $data = DokumenPribadi::whereId_ptt($this->hashid->decode($id)[0])->first();
   
            if ($request->field == 'file_ktp') {
                if ($data->file_ktp == null) return back()->with(["type" => "error", "message" => "tidak ada data yang dihapus"]);
                if (Storage::disk('local')->exists('/upload_dok_pribadi/' . $data->file_ktp) && $data->file_ktp != null) {
                    unlink(storage_path('app/upload_dok_pribadi/' . $data->file_ktp));
                    $data->file_ktp = null;
                    $data->updated_at_file_ktp = date('Y-m-d H:i:s');
                    $data->save();
                }
            } else if ($request->field == 'file_bpjs') {
                if ($data->file_bpjs == null) return back()->with(["type" => "error", "message" => "tidak ada data yang dihapus"]);
                if (Storage::disk('local')->exists('/upload_dok_pribadi/' . $data->file_bpjs) && $data->file_bpjs != null) {
                    unlink(storage_path('app/upload_dok_pribadi/' . $data->file_bpjs));
                    $data->file_bpjs = null;
                    $data->updated_at_file_bpjs = date('Y-m-d H:i:s');
                    $data->save();
                }
            } else if ($request->field == 'file_bpjs_naker') {
                if ($data->file_bpjs_naker == null) return back()->with(["type" => "error", "message" => "tidak ada data yang dihapus"]);
                if (Storage::disk('local')->exists('/upload_dok_pribadi/' . $data->file_bpjs_naker) && $data->file_bpjs_naker != null) {
                    unlink(storage_path('app/upload_dok_pribadi/' . $data->file_bpjs_naker));
                    $data->file_bpjs_naker = null;
                    $data->updated_at_file_bpjs_naker = date('Y-m-d H:i:s');
                    $data->save();
                }
            }

            return back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
