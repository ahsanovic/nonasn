<?php

namespace App\Http\Controllers\NonAsn;

use Hashids\Hashids;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\GajiNonPttRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Fasilitator\DownloadPegawai;
use App\Models\GajiNonPtt;

class NonasnGajiNonPttController extends Controller
{
    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
    }
    
    public function index()
    {
        $hashId = $this->_hashId();
        $data = GajiNonPtt::whereId_ptt(auth()->user()->id_ptt)->orderByDesc('tahun')->get();

        return view('nonasn.gaji_non_ptt.index', compact('data', 'hashId'));
    }

    public function create()
    {
        $submit = "Simpan";

        return view('nonasn.gaji_non_ptt.create', compact('submit'));
    }

    public function viewFileDpa($file)
    {
        try {
            $file = storage_path('app/upload_dpa/' . $file);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function viewFileGaji($file)
    {
        try {
            $file = storage_path('app/upload_gaji/' . $file);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    private function _uploadFileDpa($file)
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
        Storage::disk('local')->put('/upload_dpa/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    private function _uploadFileGaji($file)
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
        Storage::disk('local')->put('/upload_gaji/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function store(GajiNonPttRequest $request)
    {
        // DB::beginTransaction();
        try {
            $data = GajiNonPtt::whereId_ptt(auth()->user()->id_ptt)->whereTahun($request->tahun)->first(['tahun']);
            if ($data) return back()->with(["type" => "error", "message" => "data tahun " . $request->tahun . " sudah ada!"]);

            GajiNonPtt::create([
                'id_ptt' => auth()->user()->id_ptt,
                'tahun' => $request->tahun,
                'tmt_awal' => $request->tmt_awal,
                'tmt_akhir' => $request->tmt_akhir,
                'nominal_gaji' => $request->nominal_gaji,
                'file_dpa' => $request->hasFile('file_dpa') ? $this->_uploadFileDpa($request->file('file_dpa')) : null,
                'file_gaji' => $request->hasFile('file_gaji') ? $this->_uploadFileGaji($request->file('file_gaji')) : null
            ]);

            // update table download
            // $update = DownloadPegawai::whereId_ptt(auth()->user()->id_ptt)->first();        
            // if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            // $update->tahun_penilaian = $request->tahun;
            // $update->rekomendasi = $request->rekomendasi;
            // $update->save();

            // DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'input');

            return redirect()->route('nonasn.gajinonptt')->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            // DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function edit($id)
    {
        $submit = "Update";
        $hashId = $this->_hashId();
        $data = GajiNonPtt::findOrFail($hashId->decode($id)[0]);

        return view('nonasn.gaji_non_ptt.edit', compact('submit', 'data', 'hashId'));
    }

    public function update(GajiNonPttRequest $request, $id)
    {
        try {
            $hashId = $this->_hashId();
            $data = GajiNonPtt::whereId($hashId->decode($id)[0])->first();
            if ($request->hasFile('file_dpa')) {
                $file_dpa = $this->_uploadFileDpa($request->file('file_dpa'));
                if (Storage::disk('local')->exists('/upload_dpa/' . $data->file_dpa) && $data->file_dpa != null) {
                    unlink(storage_path('app/upload_dpa/' . $data->file_dpa));
                } else {
                    $file_dpa = $file_dpa;
                }
            } else {
                $file_dpa = $data->file_dpa;
            }

            if ($request->hasFile('file_gaji')) {
                $file_gaji = $this->_uploadFileGaji($request->file('file_gaji'));
                if (Storage::disk('local')->exists('/upload_gaji/' . $data->file_gaji) && $data->file_gaji != null) {
                    unlink(storage_path('app/upload_gaji/' . $data->file_gaji));
                } else {
                    $file_gaji = $file_gaji;
                }
            } else {
                $file_gaji = $data->file_gaji;
            }

            if ($data) {
                $data->tahun = $request->tahun;
                $data->tmt_awal = $request->tmt_awal;
                $data->tmt_akhir = $request->tmt_akhir;
                $data->nominal_gaji = $request->nominal_gaji;
                $data->file_dpa = $file_dpa;
                $data->file_gaji = $file_gaji;
                $data->save();
            }

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'update');

            return redirect()->route('nonasn.gajinonptt')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        try {
            $hashId = $this->_hashId();
            $data = GajiNonPtt::find($hashId->decode($id)[0]);

            if ($data->file_dpa && Storage::disk('local')->exists('/upload_dpa/' . $data->file_dpa)) {
                unlink(storage_path('app/upload_dpa/' . $data->file_dpa));
                $data->delete();
            } else {
                $data->delete();
            }

            if ($data->file_gaji && Storage::disk('local')->exists('/upload_gaji/' . $data->file_gaji)) {
                unlink(storage_path('app/upload_gaji/' . $data->file_gaji));
                $data->delete();
            } else {
                $data->delete();
            }

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'hapus');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
