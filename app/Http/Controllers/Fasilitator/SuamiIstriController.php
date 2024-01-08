<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Hashids\Hashids;
use App\Models\Biodata;
use App\Models\SuamiIstri;
use App\Models\RefKelasBpjs;
use App\Models\RefPekerjaan;
use Illuminate\Http\Request;
use App\Models\RefSuamiIstri;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SuamiIstriRequest;

class SuamiIstriController extends Controller
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

        $data = SuamiIstri::with(['refSuamiIstri', 'pekerjaan'])
                ->whereId_ptt($idPegawai)->get();

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.suami_istri.index', compact(
            'data',
            'pegawai',
            'skpd',
            'hashid',
            'hashidSkpd',
            'hashidPegawai'
        ));
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
        $filenameToStore = $time . '-' . uniqid() . '-' . preg_replace("/\s+/", "_", $filename) . '.' . $extension;
        // Upload file
        Storage::disk('local')->put('/upload_bpjs/' . $filenameToStore, File::get($file));

        return $filenameToStore;
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

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $status_suami_istri = RefSuamiIstri::pluck('status_suami_istri', 'status_suami_istri_id');

        $pekerjaan = RefPekerjaan::pluck('pekerjaan', 'pekerjaan_id');

        $kelas = RefKelasBpjs::pluck('kelas', 'id');

        return view('fasilitator.suami_istri.create', compact(
            'submit',
            'pegawai',
            'skpd',
            'status_suami_istri',
            'pekerjaan',
            'kelas',
            'hashidSkpd',
            'hashidPegawai'
        ));
    }

    public function store(SuamiIstriRequest $request)
    {
        try {
            list($tgl,$bln,$thn) = explode("/", $request->tgl_lahir);
            $hashidPegawai = $this->hashidPegawai;

            $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

            SuamiIstri::create([
                'id_ptt' => $idPegawai,
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

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $idPegawai, $request->segment(4), 'input');

            return redirect()
                ->route('fasilitator.suami-istri', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
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

        $pasangan = SuamiIstri::where('suami_istri_id', $id)->first();
        if (!$pasangan) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $pegawai = Biodata::whereId_ptt($idPegawai)->whereAktif('Y')->first(['id_ptt', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $status_suami_istri = RefSuamiIstri::pluck('status_suami_istri', 'status_suami_istri_id');
        
        $pekerjaan = RefPekerjaan::pluck('pekerjaan', 'pekerjaan_id');

        $kelas = RefKelasBpjs::pluck('kelas', 'id');

        return view('fasilitator.suami_istri.edit', compact(
            'submit',
            'pasangan',
            'pegawai',
            'skpd',
            'status_suami_istri',
            'pekerjaan',
            'kelas',
            'hashidSkpd',
            'hashidPegawai',
            'hashid'
        ));
    }

    public function update(SuamiIstriRequest $request)
    {
        try {
            list($tgl,$bln,$thn) = explode("/", $request->tgl_lahir);
            $hashid = $this->hashid;
            $id = $hashid->decode($request->idSuamiIstri)[0] ?? '';

            $data = SuamiIstri::whereSuami_istri_id($id)->first(['file_bpjs']);
            if ($request->hasFile('file_bpjs')) {
                $file = $this->_uploadFile($request->file('file_bpjs'));
                if (Storage::disk('local')->exists('/upload_bpjs/' . $data->file_bpjs) && $data->file_bpjs != null) {
                    unlink(storage_path('app/upload_bpjs/' . $data->file_bpjs));
                }
            } else {
                $file = $data->file_bpjs;
            }

            SuamiIstri::where('suami_istri_id', $id)
                    ->update([
                        'nama_suami_istri' => $request->nama,
                        'status_suami_istri_id' => $request->status,
                        'tempat_lahir' => $request->tempat_lahir,
                        'tgl_lahir' => $thn . '-' . $bln . '-' . $tgl,
                        'pekerjaan_id' => $request->pekerjaan,
                        'instansi' => $request->instansi,
                        'no_bpjs' => $request->no_bpjs,
                        'kelas_id' => $request->kelas,
                        'file_bpjs' => $file
                    ]);

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $this->hashidPegawai->decode($request->segment(5))[0], $request->segment(4), 'update');

            return redirect()
                ->route('fasilitator.suami-istri', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
                ->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function activate($id)
    {
        try {
            $id = $this->hashid->decode($id)[0];
            $status_aktif = SuamiIstri::where('suami_istri_id', $id)->first(['id_ptt', 'aktif']);
            if ($status_aktif->aktif == 'Y') {
                SuamiIstri::select('aktif')->where('suami_istri_id', $id)->update(['aktif' => 'N']);
                SuamiIstri::select('aktif')->where('suami_istri_id', '!=', $id)->update(['aktif' => 'Y']);
            } else {
                SuamiIstri::select('aktif')->where('suami_istri_id', $id)->update(['aktif' => 'Y']);
                SuamiIstri::select('aktif')->where('suami_istri_id', '!=', $id)->update(['aktif' => 'N']);
            }

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $status_aktif->id_ptt, request()->segment(2), 'aktif');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil diupdate!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        try {
            $data = SuamiIstri::find($this->hashid->decode($id)[0]);

            if ($data->file_bpjs) unlink(storage_path('app/upload_bpjs/' . $data->file_bpjs));
            $data->delete();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $data->id_ptt, 'suami-istri', 'hapus');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
