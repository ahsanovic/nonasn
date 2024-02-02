<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Anak;
use App\Models\Skpd;
use Hashids\Hashids;
use App\Models\Biodata;
use App\Models\SuamiIstri;
use App\Models\RefKelasBpjs;
use Illuminate\Http\Request;
use App\Models\RefStatusAnak;
use App\Models\RefPekerjaanAnak;
use App\Http\Requests\AnakRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AnakController extends Controller
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

        $data = Anak::with(['statusAnak', 'pekerjaanAnak', 'orangTua'])
                ->whereId_ptt($idPegawai)
                ->get();

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.anak.index', compact(
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
        $filenameToStore = $time . '-' . uniqid() . '.' . $extension;
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

        $status_anak = RefStatusAnak::pluck('status_anak', 'status_anak_id');

        $pekerjaan = RefPekerjaanAnak::pluck('pekerjaan', 'pekerjaan_id');

        $ortu = SuamiIstri::whereId_ptt($idPegawai)->whereAktif('Y')->first();

        $kelas = RefKelasBpjs::pluck('kelas', 'id');

        return view('fasilitator.anak.create', compact(
            'submit',
            'pegawai',
            'skpd',
            'status_anak',
            'pekerjaan',
            'ortu',
            'kelas',
            'hashidSkpd',
            'hashidPegawai'
        ));
    }

    public function store(AnakRequest $request)
    {
        try {
            list($tgl,$bln,$thn) = explode("/", $request->tgl_lahir);
            $hashidPegawai = $this->hashidPegawai;
            $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

            Anak::create([
                'id_ptt' => $idPegawai,
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

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $idPegawai, $request->segment(4), 'input');

            return redirect()
                ->route('fasilitator.anak', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
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

        $anak = Anak::where('anak_id', $id)->first();
        if (!$anak) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::select('id', 'name')->whereId($idSkpd)->first();
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $status_anak = RefStatusAnak::pluck('status_anak', 'status_anak_id');

        $pekerjaan = RefPekerjaanAnak::pluck('pekerjaan', 'pekerjaan_id');

        $ortu = SuamiIstri::whereId_ptt($idPegawai)->whereAktif('Y')->first();

        $kelas = RefKelasBpjs::pluck('kelas', 'id');

        return view('fasilitator.anak.edit', compact(
            'submit',
            'anak',
            'pegawai',
            'skpd',
            'status_anak',
            'pekerjaan',
            'ortu',
            'kelas',
            'hashidSkpd',
            'hashidPegawai',
            'hashid'
        ));
    }

    public function update(AnakRequest $request)
    {
        try {
            list($tgl,$bln,$thn) = explode("/", $request->tgl_lahir);
            $hashid = $this->hashid;
            $id = $hashid->decode($request->idAnak)[0] ?? '';

            $data = Anak::select('file_bpjs')->whereAnak_id($id)->first();
            if ($request->hasFile('file_bpjs')) {
                $file = $this->_uploadFile($request->file('file_bpjs'));
                if (Storage::disk('local')->exists('/upload_bpjs/' . $data->file_bpjs) && $data->file_bpjs != null) {
                    unlink(storage_path('app/upload_bpjs/' . $data->file_bpjs));
                }
            } else {
                $file = $data->file_bpjs;
            }

            Anak::where('anak_id', $id)
                    ->update([
                        'suami_istri_id' => $request->ortu, 
                        'nama' => $request->nama,
                        'status_anak_id' => $request->status,
                        'tempat_lahir' => $request->tempat_lahir,
                        'tgl_lahir' => $thn . '-' . $bln . '-' . $tgl,
                        'pekerjaan_anak_id' => $request->pekerjaan,
                        'no_bpjs' => $request->no_bpjs,
                        'kelas_id' => $request->kelas,
                        'file_bpjs' => $file
                    ]);

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $this->hashidPegawai->decode($request->segment(5))[0], $request->segment(4), 'update');

            return redirect()
                ->route('fasilitator.anak', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
                ->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        try {
            $data = Anak::find($this->hashid->decode($id)[0]);
            if ($data->file_bpjs) unlink(storage_path('app/upload_bpjs/' . $data->file_bpjs));
            $data->delete();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $data->id_ptt, 'anak', 'hapus');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            dd($th);
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
