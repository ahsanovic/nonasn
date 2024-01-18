<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Hashids\Hashids;
use App\Models\Biodata;
use App\Models\Jenjang;
use App\Models\Pendidikan;
use Illuminate\Http\Request;
use App\Models\RefAkreditasi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PendidikanPtRequest;
use App\Models\Fasilitator\DownloadPegawai;

class PendidikanPtController extends Controller
{
    protected $hashidSkpd;
    protected $hashidPegawai;
    protected $hashid;
    protected $hashidJenjang;

    public function __construct()
    {
        $this->hashidSkpd = $this->_hashIdSkpd();
        $this->hashidPegawai = $this->_hashIdPegawai();
        $this->hashid = $this->_hashId();
        $this->hashidJenjang = $this->_hashIdJenjang();
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

    private function _hashIdJenjang()
    {
        return new Hashids(env('SECRET_SALT_KEY'),5);
    }
    
    public function index(Request $request)
    {
        $hashidSkpd = $this->hashidSkpd;
        $hashidPegawai = $this->hashidPegawai;
        $hashid = $this->hashid;
        $hashidJenjang = $this->hashidJenjang;

        $idSkpd = $hashidSkpd->decode($request->segment(3))[0] ?? '';
        if (!$idSkpd) return back()->with(["type" => "error", "message" => "forbidden!"]);

        // check scope of id skpd
        if (!in_array($idSkpd, getScopeIdSkpd())) return back()->with(["type" => "error", "message" => "forbidden!"]);

        $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

        $data = Pendidikan::with(['jenjang'])
                    ->whereId_ptt($idPegawai)
                    ->get();

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.pendidikan.index', compact(
            'data',
            'pegawai',
            'skpd',
            'hashid',
            'hashidSkpd',
            'hashidPegawai',
            'hashidJenjang'
        ));
    }

    public function viewFileIjazah($file)
    {
        try {
            $file = storage_path('app/upload_ijazah/' . $file);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function viewFileTranskrip($file)
    {
        try {
            $file = storage_path('app/upload_transkrip/' . $file);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    private function _uploadFileIjazah($file)
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
        Storage::disk('local')->put('/upload_ijazah/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    private function _uploadFileTranskrip($file)
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
        Storage::disk('local')->put('/upload_transkrip/' . $filenameToStore, File::get($file));

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

        $jenjang = Jenjang::where('id_jenjang', '>', 3)->pluck('nama_jenjang', 'id_jenjang');

        $akreditasi = RefAkreditasi::pluck('akreditasi', 'id');

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.pendidikan.create_pt', compact(
            'submit',
            'pegawai',
            'skpd',
            'jenjang',
            'akreditasi',
            'hashidSkpd',
            'hashidPegawai'
        ));
    }

    public function store(PendidikanPtRequest $request)
    {
        try {
            $hashidPegawai = $this->hashidPegawai;
            $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

            Pendidikan::create([
                'id_ptt' => $idPegawai,
                'id_jenjang' => $request->jenjang_pt,
                'nama_pt' => $request->nama_pt,
                'fakultas_pt' => $request->fakultas_pt,
                'jurusan_prodi_pt' => $request->jurusan_prodi_pt,
                'akreditasi_pt' => $request->akreditasi_pt,
                'thn_lulus_pt' => $request->thn_lulus_pt,
                'no_ijazah_pt' => $request->no_ijazah_pt,
                'tgl_ijazah_pt' => $request->tgl_ijazah_pt,
                'ipk_pt' => $request->ipk_pt,
                'file_ijazah_pt' => $request->hasFile('file_ijazah_pt') ? $this->_uploadFileIjazah($request->file('file_ijazah_pt')) : null,
                'file_nilai_pt' => $request->hasFile('file_nilai_pt') ? $this->_uploadFileTranskrip($request->file('file_nilai_pt')) : null
            ]);

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $idPegawai, $request->segment(4), 'input');

            return redirect()
                ->route('fasilitator.pendidikan-sma', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
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

        $data = Pendidikan::whereId_ptt_pendidikan($id)->first();
        if (!$data) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $jenjang = Jenjang::where('id_jenjang', '>', 3)->pluck('nama_jenjang', 'id_jenjang');

        $akreditasi = RefAkreditasi::pluck('akreditasi', 'id');

        return view('fasilitator.pendidikan.edit_pt', compact(
            'submit',
            'data',
            'pegawai',
            'skpd',
            'jenjang',
            'akreditasi',
            'hashidSkpd',
            'hashidPegawai',
            'hashid'
        ));
    }

    public function update(PendidikanPtRequest $request)
    {
        DB::beginTransaction();
        try {
            $hashid = $this->hashid;
            $id = $hashid->decode($request->idPendidikan)[0] ?? '';

            $data = Pendidikan::whereId_ptt_pendidikan($id)->first();
            
            if ($request->hasFile('file_ijazah_pt')) {
                $file_ijazah_pt = $this->_uploadFileIjazah($request->file('file_ijazah_pt'));
                if (Storage::disk('local')->exists('/upload_ijazah/' . $data->file) && $data->file_ijazah_pt != null) {
                    unlink(storage_path('app/upload_ijazah/' . $data->file_ijazah_pt));
                }
            } else {
                $file_ijazah_pt = $data->file_ijazah_pt;
            }

            if ($request->hasFile('file_nilai_pt')) {
                $file_nilai_pt = $this->_uploadFileTranskrip($request->file('file_nilai_pt'));
                if (Storage::disk('local')->exists('/upload_transkrip/' . $data->file) && $data->file_nilai_pt != null) {
                    unlink(storage_path('app/upload_transkrip/' . $data->file_nilai_pt));
                }
            } else {
                $file_nilai_pt = $data->file_nilai_pt;
            }

            if ($data) {
                $data->id_jenjang = $request->jenjang_pt;
                $data->nama_pt = $request->nama_pt;
                $data->fakultas_pt = $request->fakultas_pt;
                $data->jurusan_prodi_pt = $request->jurusan_prodi_pt;
                $data->akreditasi_pt = $request->akreditasi_pt;
                $data->thn_lulus_pt = $request->thn_lulus_pt;
                $data->no_ijazah_pt = $request->no_ijazah_pt;
                $data->tgl_ijazah_pt = $request->tgl_ijazah_pt;
                $data->ipk_pt = $request->ipk_pt;
                $data->file_ijazah_pt = $file_ijazah_pt;
                $data->file_nilai_pt = $file_nilai_pt;
                $data->save();
            }

            if ($data->aktif == 'Y') {
                DB::beginTransaction();
                try {
                    // update table download
                    $ref_jenjang = Jenjang::whereId_jenjang($data->id_jenjang)->first(['nama_jenjang']);
                    $update = DownloadPegawai::whereId_ptt($data->id_ptt)->first();
        
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->jenjang = $ref_jenjang->nama_jenjang;
                    $update->nama_sekolah = $request->nama_pt;
                    $update->jurusan = $request->jurusan_prodi_pt;
                    $update->akreditasi = $request->akreditasi_pt;
                    $update->thn_lulus = $request->thn_lulus_pt;
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
                ->route('fasilitator.pendidikan-sma', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
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
            $hashidJenjang = $this->hashidJenjang;
            $id = $this->hashid->decode($id)[0];
            $status_aktif = Pendidikan::select('aktif')->where('id_ptt_pendidikan', $id)->first();

            if ($status_aktif->aktif == 'Y') {
                Pendidikan::where('id_ptt_pendidikan', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'N']);

                Pendidikan::where('id_ptt_pendidikan', '!=', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'Y']);
            } else {
                Pendidikan::where('id_ptt_pendidikan', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'Y']);

                Pendidikan::where('id_ptt_pendidikan', '!=', $id)
                    ->whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])
                    ->update(['aktif' => 'N']);
            }

            // update table download
            $ref_jenjang = Jenjang::whereId_jenjang($hashidJenjang->decode($request->jenjang_pt)[0])->first(['nama_jenjang']);
            $update = DownloadPegawai::whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])->first();

            if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            $update->jenjang = $ref_jenjang->nama_jenjang;
            $update->nama_sekolah = $request->nama_pt;
            $update->jurusan = $request->jurusan_prodi_pt;
            $update->akreditasi = $request->akreditasi_pt;
            $update->thn_lulus = $request->thn_lulus_pt;
            $update->save();

            DB::commit();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $hashidPegawai->decode($request->id_pegawai)[0], 'pendidikan', 'aktif');

            return redirect()->back()->with(["type" => "success", "message" => "berhasil diupdate!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = Pendidikan::find($this->hashid->decode($id)[0]);
            
            if ($data->aktif == 'Y') {
                DB::beginTransaction();
                try {
                    // update table download
                    $update = DownloadPegawai::whereId_ptt($data->id_ptt)->first();        
                    if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
                    $update->jenjang = null;
                    $update->nama_sekolah = null;
                    $update->jurusan = null;
                    $update->akreditasi = null;
                    $update->thn_lulus = null;
                    $update->save();
        
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return back()->with(["type" => "error", "message" => "gagal menghapus!"]);
                }
            }

            if ($data->file_ijazah_pt) unlink(storage_path('app/upload_ijazah/' . $data->file_ijazah_pt));
            if ($data->file_nilai_pt) unlink(storage_path('app/upload_transkrip/' . $data->file_nilai_pt));
            $data->delete();
            
            DB::commit();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $data->id_ptt, 'pendidikan', 'hapus');

            return back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
