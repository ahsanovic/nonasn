<?php

namespace App\Http\Controllers\NonAsn;

use Hashids\Hashids;
use App\Models\Jabatan;
use App\Models\RefJabatan;
use App\Models\RefGuruMapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\JabatanRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Fasilitator\DownloadPegawai;

class NonasnJabatanController extends Controller
{
    public function index()
    {
        $id_ptt = auth()->user()->id_ptt;
        $hashId = $this->_hashId();

        $data = Jabatan::with(['refJabatan'])
                    ->whereId_ptt($id_ptt)
                    ->orderByDesc('tgl_mulai')
                    ->get();

        return view('nonasn.jabatan.index', compact('data', 'hashId'));
    }

    public function treeview()
    {
        $jabatan = RefJabatan::orderBy('id_jabatan')->get();
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

    public function create()
    {
        $submit = "Simpan";

        return view('nonasn.jabatan.create', compact('submit'));
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
        Storage::disk('local')->put('/upload_jabatan/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function store(JabatanRequest $request)
    {
        try {
            Jabatan::create([
                'id_ptt' => auth()->user()->id_ptt,
                'id_jabatan' => explode(" - ", $request->jabatan)[0],
                'id_guru_mapel' => (auth()->user()->jenis_ptt_id == 4) ? $request->id_guru_mapel : null,
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

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'input');

            return redirect()->route('nonasn.jabatan')->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function edit($id)
    {
        $submit = "Update";
        $hashId = $this->_hashId();

        $jab = Jabatan::findOrFail($hashId->decode($id)[0]);
        $mapel = RefGuruMapel::whereId($jab->id_guru_mapel)->first();

        return view('nonasn.jabatan.edit', compact('submit', 'jab', 'hashId', 'mapel'));
    }

    public function update(JabatanRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $hashId = $this->_hashId();

            $data = Jabatan::whereId_ptt_jab($hashId->decode($id)[0])->first();
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

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'update');

            return redirect()->route('nonasn.jabatan')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function activate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $hashId = $this->_hashId();
            $id = $hashId->decode($id)[0];

            $status_aktif = Jabatan::where('id_ptt_jab', $id)->first(['aktif']);
            if ($status_aktif->aktif == 'Y') {
                Jabatan::where('id_ptt_jab', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'N']);

                Jabatan::where('id_ptt_jab', '!=', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'Y']);
            } else {
                Jabatan::where('id_ptt_jab', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'Y']);

                Jabatan::where('id_ptt_jab', '!=', $id)
                    ->whereId_ptt(auth()->user()->id_ptt)
                    ->update(['aktif' => 'N']);
            }

            // update table download
            $update = DownloadPegawai::whereId_ptt(auth()->user()->id_ptt)->first();
            if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            $update->jabatan = $request->jabatan;
            $update->no_sk = $request->no_sk;
            $update->tgl_sk = $request->tgl_sk;
            $update->tgl_mulai = $request->tgl_mulai;
            $update->tgl_akhir = $request->tgl_akhir;
            $update->save();

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'aktif');

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
            $hashId = $this->_hashId();
            $data = Jabatan::find($hashId->decode($id)[0]);

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

            if ($data->file) unlink(storage_path('app/upload_jabatan/' . $data->file));
            $data->delete();

            DB::commit();

            logPtt(auth()->user()->id_ptt, request()->segment(1), 'hapus');

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
