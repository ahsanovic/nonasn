<?php

namespace App\Http\Controllers\NonAsn;

use Hashids\Hashids;
use App\Models\Diklat;
use Illuminate\Http\Request;
use App\Models\RefJenisDiklat;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiklatRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class NonasnDiklatController extends Controller
{
    public function viewFile($file)
    {
        try {
            $file = storage_path('app/upload_diklat/' . $file);
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
        Storage::disk('local')->put('/upload_diklat/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }
    
    public function index()
    {
        $id_ptt = auth()->user()->id_ptt;
        $hashId = $this->_hashId();

        $data = Diklat::with('jenisDiklat')->whereId_ptt($id_ptt)->latest()->get();
        
        return view('nonasn.diklat.index', compact('data', 'hashId'));
    }

    public function create()
    {
        $submit = "Simpan";
        $jenis_diklat = RefJenisDiklat::pluck('jenis_diklat', 'id');
        return view('nonasn.diklat.create', compact('submit', 'jenis_diklat'));
    }

    public function store(DiklatRequest $request)
    {
        try {
            Diklat::create([
                'id_ptt' => auth()->user()->id_ptt,
                'jenis_diklat_id' => $request->jenis_diklat,
                'nama_diklat' => $request->nama_diklat,
                'no_sertifikat' => $request->no_sertifikat,
                'tgl_sertifikat' => $request->tgl_sertifikat,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_selesai' => $request->tgl_selesai,
                'penyelenggara' => $request->penyelenggara,
                'jml_jam' => $request->jml_jam,
                'file' => $request->hasFile('file') ? $this->_uploadFile($request->file('file')) : null
            ]);

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'input');

            return redirect()->route('nonasn.diklat')->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function edit($id)
    {
        $submit = "Update";
        $hashId = $this->_hashId();

        $jenis_diklat = RefJenisDiklat::pluck('jenis_diklat', 'id');
        $data = Diklat::findOrFail($hashId->decode($id)[0]);

        return view('nonasn.diklat.edit', compact('data', 'submit', 'hashId', 'jenis_diklat'));
    }

    public function update(DiklatRequest $request, $id)
    {
        try {
            $hashId = $this->_hashId();

            $data = Diklat::whereId($hashId->decode($id)[0])->first();
            if ($request->hasFile('file')) {
                $file = $this->_uploadFile($request->file('file'));
                if (Storage::disk('local')->exists('/upload_diklat/' . $data->file) && $data->file != null) {
                    unlink(storage_path('app/upload_diklat/' . $data->file));
                }
            } else {
                $file = $data->file;
            }

            if ($data) {
                $data->jenis_diklat_id = $request->jenis_diklat;
                $data->nama_diklat = $request->nama_diklat;
                $data->no_sertifikat = $request->no_sertifikat;
                $data->tgl_sertifikat = $request->tgl_sertifikat;
                $data->tgl_mulai = $request->tgl_mulai;
                $data->tgl_selesai = $request->tgl_selesai;
                $data->penyelenggara = $request->penyelenggara;
                $data->jml_jam = $request->jml_jam;
                $data->file = $file;
                $data->save();
            }

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'update');

            return redirect()->route('nonasn.diklat')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        try {
            $hashId = $this->_hashId();
            $data = Diklat::find($hashId->decode($id)[0]);

            if (Storage::disk('local')->exists('/upload_diklat/' . $data->file) && $data->file != null) {
                unlink(storage_path('app/upload_diklat/' . $data->file));
            }
            
            $data->delete();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'hapus');

            return back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
