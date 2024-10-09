<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Hashids\Hashids;
use App\Models\Biodata;
use App\Models\GajiNonPtt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\GajiNonPttRequest;

class GajiNonPttController extends Controller
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

        $data = GajiNonPtt::whereId_ptt($idPegawai)->orderByDesc('tahun')->get();

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'jenis_ptt_id', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.gaji_non_ptt.index', compact(
            'data',
            'pegawai',
            'skpd',
            'hashidSkpd',
            'hashidPegawai',
            'hashid'
        ));
    }

    public function create(Request $request)
    {
        $submit = "Simpan";
        $hashidSkpd = $this->hashidSkpd;
        $hashidPegawai = $this->hashidPegawai;

        $idSkpd = $hashidSkpd->decode($request->segment(3))[0] ?? '';
        if (!$idSkpd) return back()->with(["type" => "error", "message" => "forbidden!"]);

        // check scope of id skpd
        if (!in_array($idSkpd, getScopeIdSkpd())) return back()->with(["type" => "error", "message" => "forbidden!"]);

        $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'jenis_ptt_id', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.gaji_non_ptt.create', compact(
            'submit',
            'pegawai',
            'skpd',
            'hashidSkpd',
            'hashidPegawai'
        ));
    }

    public function viewFile($file)
    {
        try {
            $file = storage_path('app/upload_gaji/' . $file);
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
        Storage::disk('local')->put('/upload_gaji/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function store(GajiNonPttRequest $request)
    {
        try {
            $hashidPegawai = $this->hashidPegawai;
            $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';
            
            $data = GajiNonPtt::whereId_ptt($idPegawai)->whereTahun($request->tahun)->first(['tahun']);
            if ($data) return back()->with(["type" => "error", "message" => "data tahun " . $request->tahun . " sudah ada!"]);

            GajiNonPtt::create([
                'id_ptt' => $idPegawai,
                'tahun' => $request->tahun,
                'tmt_awal' => $request->tmt_awal,
                'tmt_akhir' => $request->tmt_akhir,
                'nominal_gaji' => $request->nominal_gaji,
                'link_gdrive' => $request->link_gdrive,
                'file_gaji' => $request->hasFile('file_gaji') ? $this->_uploadFile($request->file('file_gaji')) : null
            ]);

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $idPegawai, $request->segment(4), 'input');

            return redirect()
                ->route('fasilitator.gajinonptt', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
                ->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function edit(Request $request)
    {
        $submit = "Update";
        $hashidSkpd = $this->hashidSkpd;
        $hashidPegawai = $this->hashidPegawai;
        $hashid = $this->hashid;

        $idSkpd = $hashidSkpd->decode($request->segment(3))[0] ?? '';
        if (!$idSkpd) return back()->with(["type" => "error", "message" => "forbidden!"]);

        // check scope of id skpd
        if (!in_array($idSkpd, getScopeIdSkpd())) return back()->with(["type" => "error", "message" => "forbidden!"]);

        $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';
        $id = $hashid->decode($request->segment(7))[0] ?? '';

        $data = GajiNonPtt::whereId($id)->first();
        if (!$data) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'jenis_ptt_id', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.gaji_non_ptt.edit', compact(
            'submit',
            'data',
            'pegawai',
            'skpd',
            'hashidSkpd',
            'hashidPegawai',
            'hashid'
        ));
    }

    public function update(GajiNonPttRequest $request)
    {
        try {
            $hashid = $this->hashid;
            $id = $hashid->decode($request->idGaji)[0] ?? '';

            $data = GajiNonPtt::whereId($id)->first();
            if ($request->hasFile('file_gaji')) {
                $file_gaji = $this->_uploadFile($request->file('file_gaji'));
                if (Storage::disk('local')->exists('/upload_gaji/' . $data->file_gaji) && $data->file_gaji != null) {
                    Storage::delete('upload_gaji/' . $data->file_gaji);
                }

                $data->file_gaji = $file_gaji;
            }

            if ($request->link_gdrive) {
                $data->link_gdrive = $request->link_gdrive;
            }

            $data->tahun = $request->tahun;
            $data->tmt_awal = $request->tmt_awal;
            $data->tmt_akhir = $request->tmt_akhir;
            $data->nominal_gaji = $request->nominal_gaji;
            $data->save();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $this->hashidPegawai->decode($request->segment(5))[0], $request->segment(4), 'update');

            return redirect()
                ->route('fasilitator.gajinonptt', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
                ->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        try {
            $data = GajiNonPtt::find($this->hashid->decode($id)[0]);
            
            if ($data->file_gaji && Storage::disk('local')->exists('/upload_gaji/' . $data->file_gaji)) {
                unlink(storage_path('app/upload_gaji/' . $data->file_gaji));
                $data->delete();
            } else {
                $data->delete();
            }

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $data->id_ptt, 'gaji', 'hapus');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
