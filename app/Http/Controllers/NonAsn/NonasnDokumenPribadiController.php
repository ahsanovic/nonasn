<?php

namespace App\Http\Controllers\NonAsn;

use Hashids\Hashids;
use App\Models\RefDokumen;
use Illuminate\Http\Request;
use App\Models\DokumenPribadi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class NonasnDokumenPribadiController extends Controller
{
    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
    }
    
    public function index()
    {
        $hashId = $this->_hashId();
        $refDokumen = RefDokumen::all();

        $data = DokumenPribadi::whereId_ptt(auth()->user()->id_ptt)->first();

        return view('nonasn.dok_pribadi.index', compact('data', 'refDokumen', 'hashId'));
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
        $filenameToStore = $time . '-' . uniqid() . '-' . preg_replace("/\s+/", "_", $filename) . '.' . $extension;
        // Upload file
        Storage::disk('local')->put('/upload_dok_pribadi/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function edit($id)
    {
        $hashId = $this->_hashId();
        $data = DokumenPribadi::findOrFail($hashId->decode($id)[0]);

        return view('nonasn.dok_pribadi.edit', compact('data', 'hashId'));
    }

    public function update(Request $request, $id)
    {
        $hashId = $this->_hashId();
        if ($request->field == 'file_ktp') {
            $request->validate([
                'file_ktp' => ['required','file','mimes:jpg,png','max:512']
            ], [
                'file_ktp.required' => 'dokumen harus diupload',
                'file_ktp.mimes' => 'dokumen harus berformat jpg/png',
                'file_ktp.max' => 'dokumen maksimal berukuran 512 kilobytes',
            ]);

            $data = DokumenPribadi::whereId($hashId->decode($id)[0])->first();
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
                'file_bpjs' => ['required','file','mimes:jpg,png','max:512'],
                'file_bpjs.mimes' => 'dokumen harus berformat jpg/png',
                'file_bpjs.max' => 'dokumen maksimal berukuran 512 kilobytes',
            ], [
                'file_bpjs.required' => 'dokumen harus diupload',
                'file_bpjs.mimes' => 'dokumen harus berformat jpg/png',
                'file_bpjs.max' => 'dokumen maksimal berukuran 512 kilobytes',
            ]);

            $data = DokumenPribadi::whereId($hashId->decode($id)[0])->first();
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
                'file_bpjs_naker' => ['required','file','mimes:jpg,png','max:512']
            ],  [
                'file_bpjs_naker.required' => 'dokumen harus diupload',
                'file_bpjs_naker.mimes' => 'dokumen harus berformat jpg/png',
                'file_bpjs_naker.max' => 'dokumen maksimal berukuran 512 kilobytes',
            ]);

            $data = DokumenPribadi::whereId($hashId->decode($id)[0])->first();
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

        logPtt(auth()->user()->id_ptt, request()->segment(1), 'update');

        return redirect()->route('nonasn.dok-pribadi')->with(["type" => "success", "message" => "berhasil diubah!"]);
    }
}
