<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Hashids\Hashids;
use App\Models\Hukdis;
use App\Models\Biodata;
use App\Models\RefHukdis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\HukdisRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HukdisController extends Controller
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

    public function viewFile($file)
    {
        try {
            $file = storage_path('app/upload_hukdis/' . $file);
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
        Storage::disk('local')->put('/upload_hukdis/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function index(Request  $request)
    {
        $hashidSkpd = $this->hashidSkpd;
        $hashidPegawai = $this->hashidPegawai;
        $hashid = $this->hashid;

        $idSkpd = $hashidSkpd->decode($request->segment(3))[0] ?? '';
        if (!$idSkpd) return back()->with(["type" => "error", "message" => "forbidden!"]);

        // check scope of id skpd
        if (!in_array($idSkpd, getScopeIdSkpd())) return back()->with(["type" => "error", "message" => "forbidden!"]);

        $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

        $data = Hukdis::with('jenisHukdis')
                    ->whereId_ptt($idPegawai)
                    ->orderByDesc('id')
                    ->get();

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.hukdis.index', compact(
            'data',
            'pegawai',
            'skpd',
            'hashid',
            'hashidSkpd',
            'hashidPegawai',
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
                    ->first(['id_ptt', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $jenis_hukdis = RefHukdis::pluck('jenis_hukdis', 'id');

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.hukdis.create', compact(
            'jenis_hukdis',
            'submit',
            'skpd',
            'pegawai',
            'hashidSkpd',
            'hashidPegawai'
        ));
    }

    public function store(HukdisRequest $request)
    {
        try {
            $hashidPegawai = $this->hashidPegawai;
            $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

            Hukdis::create([
                'id_ptt' => $idPegawai,
                'jenis_hukdis_id' => $request->jenis_hukdis_id,
                'no_sk' => $request->no_sk,
                'tgl_sk' => $request->tgl_sk,
                'tmt_awal' => $request->tmt_awal,
                'keterangan' => $request->keterangan,
                'file_hukdis' => $request->hasFile('file_hukdis') ? $this->_uploadFile($request->file('file_hukdis')) : null,
            ]);

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $idPegawai, $request->segment(4), 'input');

            return redirect()
                ->route('fasilitator.hukdis', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
                ->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            // throw $th;
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

        $data = Hukdis::whereId($id)->first();
        if (!$data) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $jenis_hukdis = RefHukdis::pluck('jenis_hukdis', 'id');

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.hukdis.edit', compact(
            'jenis_hukdis',
            'data',
            'submit',
            'skpd',
            'pegawai',
            'hashidSkpd',
            'hashidPegawai',
            'hashid'
        ));
    }

    public function update(HukdisRequest $request)
    {
        try {
            $hashid = $this->hashid;
            $id = $hashid->decode($request->idHukdis)[0] ?? '';

            $data = Hukdis::whereId($id)->first();
            if ($request->hasFile('file')) {
                $file = $this->_uploadFile($request->file('file'));
                if (Storage::disk('local')->exists('/upload_hukdis/' . $data->file_hukdis) && $data->file_hukdis != null) {
                    unlink(storage_path('app/upload_hukdis/' . $data->file_hukdis));
                }
            } else {
                $file = $data->file_hukdis;
            }

            if ($data) {
                $data->jenis_hukdis_id = $request->jenis_hukdis_id;
                $data->no_sk = $request->no_sk;
                $data->tgl_sk = $request->tgl_sk;
                $data->tmt_awal = $request->tmt_awal;
                $data->keterangan = $request->keterangan;
                $data->file_hukdis = $file;
                $data->save();
            }

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $this->hashidPegawai->decode($request->segment(5))[0], $request->segment(4), 'update');

            return redirect()
                ->route('fasilitator.hukdis', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
                ->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function activate(Request $request, $id)
    {
        try {
            $hashidPegawai = $this->hashidPegawai;
            $id = $this->hashid->decode($id)[0];

            $status_aktif = Hukdis::where('id', $id)->first(['aktif']);
            if ($status_aktif->aktif == 'Y') {
                Hukdis::where('id', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'N']);

                Hukdis::where('id', '!=', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'Y']);
            } else {
                Hukdis::where('id', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'Y']);

                Hukdis::where('id', '!=', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'N']);
            }

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $hashidPegawai->decode($request->id_pegawai)[0], 'hukdis', 'aktif');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil diupdate!"]);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        try {
            $data = Hukdis::findOrFail($this->hashid->decode($id)[0]);

            if ($data->file_hukdis && Storage::disk('local')->exists('/upload_hukdis/' . $data->file_hukdis)) {
                unlink(storage_path('app/upload_hukdis/' . $data->file_hukdis));
                $data->delete();
            }

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $data->id_ptt, 'hukdis', 'hapus');

            return back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
