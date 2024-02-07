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
use App\Http\Requests\PendidikanSmaRequest;
use App\Models\Fasilitator\DownloadPegawai;

class PendidikanSmaController extends Controller
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
                    ->orderByDesc('id_jenjang')
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

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $jenjang = Jenjang::where('id_jenjang', '<', 4)->pluck('nama_jenjang', 'id_jenjang');

        $akreditasi = RefAkreditasi::pluck('akreditasi', 'id');

        return view('fasilitator.pendidikan.create_sma', compact(
            'submit',
            'pegawai',
            'skpd',
            'jenjang',
            'akreditasi',
            'hashidSkpd',
            'hashidPegawai'
        ));
    }

    public function store(PendidikanSmaRequest $request)
    {
        try {
            $hashidPegawai = $this->hashidPegawai;
            $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';

            Pendidikan::create([
                'id_ptt' => $idPegawai,
                'id_jenjang' => $request->jenjang_sma,
                'nama_sekolah_sma' => $request->nama_sekolah,
                'jurusan_sma' => $request->jurusan_sma,
                'akreditasi_sma' => $request->akreditasi_sma,
                'thn_lulus_sma' => $request->thn_lulus_sma,
                'no_ijazah_sma' => $request->no_ijazah_sma,
                'tgl_ijazah_sma' => $request->tgl_ijazah_sma,
                'nilai_akhir_sma' => $request->nilai_akhir_sma,
                'nilai_un_sma' => $request->nilai_un_sma,
                'file_ijazah_sma' => $request->hasFile('file_ijazah_sma') ? $this->_uploadFileIjazah($request->file('file_ijazah_sma')) : null,
                'file_nilai_sma' => $request->hasFile('file_nilai_sma') ? $this->_uploadFileTranskrip($request->file('file_nilai_sma')) : null
            ]);

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $idPegawai, $request->segment(4), 'input');

            return redirect()
                ->route('fasilitator.pendidikan-sma', ['idSkpd' => $request->segment(3), 'id' => $request->segment(5)])
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

        $data = Pendidikan::whereId_ptt_pendidikan($id)->first();
        if (!$data) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $pegawai = Biodata::whereId_ptt($idPegawai)
                    ->whereAktif('Y')
                    ->first(['id_ptt', 'id_skpd', 'nama', 'foto']);
        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        $jenjang = Jenjang::where('id_jenjang', '<', 4)->pluck('nama_jenjang', 'id_jenjang');

        $akreditasi = RefAkreditasi::pluck('akreditasi', 'id');

        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.pendidikan.edit_sma', compact(
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

    public function update(PendidikanSmaRequest $request)
    {
        DB::beginTransaction();
        try {
            $hashid = $this->hashid;
            $id = $hashid->decode($request->idPendidikan)[0] ?? '';

            $data = Pendidikan::whereId_ptt_pendidikan($id)->first();
            
            if ($request->hasFile('file_ijazah_sma')) {
                $file_ijazah_sma = $this->_uploadFileIjazah($request->file('file_ijazah_sma'));
                if (Storage::disk('local')->exists('/upload_ijazah/' . $data->file_ijazah_sma) && $data->file_ijazah_sma != null) {
                    unlink(storage_path('app/upload_ijazah/' . $data->file_ijazah_sma));
                } else {
                    $file_ijazah_sma = $file_ijazah_sma;
                }
            } else {
                $file_ijazah_sma = $data->file_ijazah_sma;
            }

            if ($request->hasFile('file_nilai_sma')) {
                $file_nilai_sma = $this->_uploadFileTranskrip($request->file('file_nilai_sma'));
                if (Storage::disk('local')->exists('/upload_transkrip/' . $data->file_nilai_sma) && $data->file_nilai_sma != null) {
                    unlink(storage_path('app/upload_transkrip/' . $data->file_nilai_sma));
                } else {
                    $file_nilai_sma = $file_nilai_sma;
                }
            } else {
                $file_nilai_sma = $data->file_nilai_sma;
            }

            if ($data) {
                $data->id_jenjang = $request->jenjang_sma;
                $data->nama_sekolah_sma = $request->nama_sekolah;
                $data->jurusan_sma = $request->jurusan_sma;
                $data->akreditasi_sma = $request->akreditasi_sma;
                $data->thn_lulus_sma = $request->thn_lulus_sma;
                $data->no_ijazah_sma = $request->no_ijazah_sma;
                $data->tgl_ijazah_sma = $request->tgl_ijazah_sma;
                $data->nilai_akhir_sma = $request->nilai_akhir_sma;
                $data->nilai_un_sma = $request->nilai_un_sma;
                $data->file_ijazah_sma = $file_ijazah_sma;
                $data->file_nilai_sma = $file_nilai_sma;
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
                    $update->nama_sekolah = $request->nama_sekolah;
                    $update->jurusan = $request->jurusan_sma;
                    $update->akreditasi = $request->akreditasi_sma;
                    $update->thn_lulus = $request->thn_lulus_sma;
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
            $status_aktif = Pendidikan::where('id_ptt_pendidikan', $id)->first(['aktif']);

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
            $ref_jenjang = Jenjang::whereId_jenjang($hashidJenjang->decode($request->jenjang_sma)[0])->first(['nama_jenjang']);
            $update = DownloadPegawai::whereId_ptt($hashidPegawai->decode($request->id_pegawai)[0])->first();

            if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            $update->jenjang = $ref_jenjang->nama_jenjang;
            $update->nama_sekolah = $request->nama_sekolah_sma;
            $update->jurusan = $request->jurusan_sma;
            $update->akreditasi = $request->akreditasi_sma;
            $update->thn_lulus = $request->thn_lulus_sma;
            $update->save();

            DB::commit();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $hashidPegawai->decode($request->id_pegawai)[0], 'pendidikan', 'aktif');

            return back()->with(["type" => "success", "message" => "berhasil diupdate!"]);
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

            // if ($data->file_ijazah_sma) unlink(storage_path('app/upload_ijazah/' . $data->file_ijazah_sma));
            // if ($data->file_nilai_sma) unlink(storage_path('app/upload_transkrip/' . $data->file_nilai_sma));
            // $data->delete();

            if ($data->file_ijazah_sma && Storage::disk('local')->exists('/upload_ijazah/' . $data->file_ijazah_sma)) {
                unlink(storage_path('app/upload_ijazah/' . $data->file_ijazah_sma));
                $data->delete();
            } else {
                $data->delete();
            }

            if ($data->file_nilai_sma && Storage::disk('local')->exists('/upload_transkrip/' . $data->file_nilai_sma)) {
                unlink(storage_path('app/upload_transkrip/' . $data->file_nilai_sma));
                $data->delete();
            } else {
                $data->delete();
            } 

            DB::commit();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $data->id_ptt, 'pendidikan', 'hapus');

            return back()->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
