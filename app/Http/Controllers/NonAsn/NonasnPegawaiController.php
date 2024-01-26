<?php

namespace App\Http\Controllers\NonAsn;

use App\Models\Skpd;
use Hashids\Hashids;
use App\Models\Biodata;
use App\Models\RefAgama;
use App\Models\RefKawin;
use App\Models\RefJenisPtt;
use App\Models\RefKelasBpjs;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\NonasnBiodataRequest;
use App\Models\Fasilitator\DownloadPegawai;

class NonasnPegawaiController extends Controller
{
    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
    }
    
    public function index()
    {
        $id_ptt = auth()->user()->id_ptt;
        $hashId = $this->_hashId();
        $biodata = Biodata::whereId_ptt($id_ptt)->first([
            'id_ptt',
            'id_skpd',
            'jenis_ptt_id',
            'nama',
            'niptt',
            'tempat_lahir',
            'thn_lahir',
            'jk',
            'foto',
            'nik',
            'kk',
            'no_bpjs',
            'kelas_id',
            'no_bpjs_naker',
            'jk',
            'id_agama',
            'id_kawin',
            'alamat',
            'kode_pos',
            'no_hp',
            'email'
        ]);

        $ref_agama = RefAgama::pluck('agama', 'id_agama');

        $ref_kawin = RefKawin::pluck('status_kawin', 'id_kawin');

        $skpd = Skpd::whereId(auth()->user()->id_skpd)->first(['name']);

        $kelas = RefKelasBpjs::pluck('kelas', 'id');

        $ref_jenis_ptt = RefJenisPtt::pluck('jenis_ptt', 'id');

        return view('nonasn.biodata.index', compact('biodata', 'ref_agama', 'ref_kawin', 'skpd', 'kelas', 'hashId', 'ref_jenis_ptt'));
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
        // $file->move(public_path('upload_foto'), $filenameToStore);
        Storage::disk('local')->put('/upload_foto/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function update(NonasnBiodataRequest $request)
    {
        DB::beginTransaction();
        try {
            $alamat = $request->alamat . '|' . $request->rt . '|' . $request->rw . '|' . $request->desa . '|' . $request->kec . '|' . $request->kab . '|' . $request->prov;
            $hashId = $this->_hashId();
            $data = Biodata::whereId_ptt($hashId->decode($request->id)[0])->first();
            if ($request->hasFile('foto')) {
                $file = $this->_uploadFile($request->file('foto'));
                if (storage_path('app/upload_foto/' . $data->foto) && $data->foto != null && $data->foto != '') {
                    unlink(storage_path('app/upload_foto/' . $data->foto));
                }
            } else {
                $file = $data->foto;
            }

            if ($data) {
                // $data->nama = $request->nama;
                $data->nik = $request->nik;
                $data->kk = $request->kk;
                $data->kelas_id = $request->kelas;
                $data->no_bpjs = $request->no_bpjs;
                $data->no_bpjs_naker = $request->no_bpjs_naker;
                $data->tempat_lahir = $request->tempat_lahir;
                $data->thn_lahir = $request->thn_lahir;
                $data->jk = $request->jk;
                $data->id_agama = $request->agama;
                $data->id_kawin = $request->kawin;
                $data->alamat = $alamat;
                $data->kode_pos = $request->kode_pos;
                $data->email = $request->email;
                $data->no_hp = $request->no_hp;
                $data->foto = $file;
                $data->save();
            }

            // update table download
            $ref_agama = RefAgama::whereId_agama($request->agama)->first(['agama']);
            $ref_status_kawin = RefKawin::whereId_kawin($request->kawin)->first(['status_kawin']);
            $update = DownloadPegawai::whereId_ptt($hashId->decode($request->id)[0])->first();

            if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            // $update->nama = $request->nama;
            $update->tempat_lahir = $request->tempat_lahir;
            $update->tgl_lahir = $request->thn_lahir;
            $update->jk = $request->jk;
            $update->nik = $request->nik;
            $update->agama = $ref_agama->agama;
            $update->status_kawin = $ref_status_kawin->status_kawin;
            $update->alamat = $alamat;
            $update->kode_pos = $request->kode_pos;
            $update->no_hp = $request->no_hp;
            $update->no_bpjs = $request->no_bpjs;
            $update->kelas = $request->kelas;
            $update->no_bpjs_naker = $request->no_bpjs_naker;
            $update->save();

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'update');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil diupdate!"]);
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
