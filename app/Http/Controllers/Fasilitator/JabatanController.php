<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Hashids\Hashids;
use App\Models\Biodata;
use App\Models\Jabatan;
use App\Models\RefJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\JabatanRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Fasilitator\DownloadPegawai;
use App\Models\RefGuruMapel;

class JabatanController extends Controller
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

        $data = Jabatan::with(['refJabatan'])
                    ->whereId_ptt($idPegawai)
                    ->orderByDesc('tgl_mulai')
                    ->get();

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'jenis_ptt_id', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.jabatan.index', compact(
            'data',
            'pegawai',
            'skpd',
            'hashid',
            'hashidSkpd',
            'hashidPegawai'
        ));
    }

    public function treeview()
    {
        $jabatan = RefJabatan::where('id_jabatan', 'like', '15%')->orWhere('id_jabatan', 'like', '16%')->orderBy('id_jabatan')->get();
        $tree = [];
        foreach ($jabatan as $jab) {
            $array = [
                'id' => $jab->id_jabatan,
                'pId' => $jab->pId,
                'name' => $jab->id_jabatan . ' - ' . $jab->name,
                'url' => $jab->url,
                'icon' => url('/zTree/css/zTreeStyle/img/diy/1_open.png'),
                'open' => $jab->open
            ];
            array_push($tree, $array);
        }
        return response()->json($tree);
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

        return view('fasilitator.jabatan.create', compact(
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
            $file = storage_path('app/upload_jabatan/' . $file);
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
        Storage::disk('local')->put('/upload_jabatan/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function store(JabatanRequest $request)
    {
        try {
            $hashidPegawai = $this->hashidPegawai;
            $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

            Jabatan::create([
                'id_ptt' => $idPegawai,
                'id_jabatan' => explode(" - ", $request->jabatan)[0],
                'id_guru_mapel' => $request->id_guru_mapel,
                'no_surat' => $request->no_surat,
                'tgl_surat' => $request->tgl_surat,
                'pejabat_penetap' => $request->pejabat_penetap,
                'tgl_surat' => $request->tgl_surat,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_akhir' => $request->tgl_akhir,
                'gaji' => $request->gaji,
                'ket' => $request->ket,
                'file' => $request->hasFile('file') ? $this->_uploadFile($request->file('file')) : null
            ]);

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $idPegawai, $request->segment(4), 'input');

            return redirect()
                ->route('fasilitator.jabatan', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
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

        $jab = Jabatan::whereId_ptt_jab($id)->first();
        if (!$jab) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $mapel = RefGuruMapel::whereId($jab->id_guru_mapel)->first();

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'jenis_ptt_id', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.jabatan.edit', compact(
            'submit',
            'jab',
            'pegawai',
            'skpd',
            'hashidSkpd',
            'hashidPegawai',
            'hashid',
            'mapel'
        ));
    }

    public function update(JabatanRequest $request)
    {
        DB::beginTransaction();
        try {
            $hashid = $this->hashid;
            $id = $hashid->decode($request->idJabatan)[0] ?? '';

            $data = Jabatan::whereId_ptt_jab($id)->first();
            if ($request->hasFile('file')) {
                $file = $this->_uploadFile($request->file('file'));
                if (Storage::disk('local')->exists('/upload_jabatan/' . $data->file) && $data->file != null) {
                    unlink(storage_path('app/upload_jabatan/' . $data->file));
                }
            } else {
                $file = $data->file;
            }

            if ($data) {
                $data->id_jabatan = explode(" - ", $request->jabatan)[0];
                $data->id_guru_mapel = $request->id_guru_mapel ?? null;
                $data->no_surat = $request->no_surat;
                $data->tgl_surat = $request->tgl_surat;
                $data->pejabat_penetap = $request->pejabat_penetap;
                $data->tgl_surat = $request->tgl_surat;
                $data->tgl_mulai = $request->tgl_mulai;
                $data->tgl_akhir = $request->tgl_akhir;
                $data->gaji = $request->gaji;
                $data->ket = $request->ket;
                $data->file = $file;
                $data->save();
            }

            if ($data->aktif == 'Y') {
                DB::beginTransaction();
                try {
                    // update table download
                    $update = DownloadPegawai::whereId_ptt($data->id_ptt)->first();        
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->jabatan = explode(" - ", $request->jabatan)[1];
                    $update->no_sk = $request->no_surat;
                    $update->tgl_sk = $request->tgl_surat;
                    $update->tgl_mulai = $request->tgl_mulai;
                    $update->tgl_akhir = $request->tgl_akhir;
                    $update->save();
        
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return back()->with(["type" => "error", "message" => "gagal update!"]);
                }
            }

            DB::commit();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $this->hashidPegawai->decode($request->segment(5))[0], $request->segment(4), 'update');

            return redirect()
                ->route('fasilitator.jabatan', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
                ->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function activate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $hashidPegawai = $this->hashidPegawai;
            $id = $this->hashid->decode($id)[0];

            $status_aktif = Jabatan::where('id_ptt_jab', $id)->first(['aktif']);
            if ($status_aktif->aktif == 'Y') {
                Jabatan::where('id_ptt_jab', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'N']);

                Jabatan::where('id_ptt_jab', '!=', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'Y']);
            } else {
                Jabatan::where('id_ptt_jab', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'Y']);

                Jabatan::where('id_ptt_jab', '!=', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'N']);
            }

            // update table download
            $update = DownloadPegawai::whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])->first();
            if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            $update->jabatan = $request->jabatan;
            $update->no_sk = $request->no_sk;
            $update->tgl_sk = $request->tgl_sk;
            $update->tgl_mulai = $request->tgl_mulai;
            $update->tgl_akhir = $request->tgl_akhir;
            $update->save();

            DB::commit();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $hashidPegawai->decode($request->id_pegawai)[0], 'jabatan', 'aktif');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil diupdate!"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = Jabatan::find($this->hashid->decode($id)[0]);

            if ($data->aktif == 'Y') {
                DB::beginTransaction();
                try {
                    // update table download
                    $update = DownloadPegawai::whereId_ptt($data->id_ptt)->first();        
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->jabatan = null;
                    $update->no_sk = null;
                    $update->tgl_sk = null;
                    $update->tgl_mulai = null;
                    $update->tgl_akhir = null;
                    $update->save();
        
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return back()->with(["type" => "error", "message" => "gagal menghapus!"]);
                }
            }

            if (Storage::disk('local')->exists('/upload_jabatan/' . $data->file) && $data->file != null) {
                unlink(storage_path('app/upload_jabatan/' . $data->file));
            }

            $data->delete();

            DB::commit();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $data->id_ptt, 'jabatan', 'hapus');

            return back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function autocomplete(Request $request)
    {
        $data = RefGuruMapel::where('guru_mapel', 'like', '%'. $request->search . '%')
                ->limit(10)
                ->get();
        
        $res = [];
        foreach ($data as $value) {
            $res[] = [
                'label' => $value->guru_mapel,
                'value' => $value->id
            ];
        }
        return response()->json($res);
    }
}
