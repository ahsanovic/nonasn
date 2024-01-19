<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Hashids\Hashids;
use App\Models\Biodata;
use App\Models\RefAgama;
use App\Models\RefKawin;
use App\Models\RefJenisPtt;
use App\Models\RefKelasBpjs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\BiodataRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Fasilitator\DownloadPegawai;

class PegawaiController extends Controller
{
    protected $hashidSkpd;
    protected $hashidPegawai;

    public function __construct()
    {
        $this->hashidSkpd = $this->_hashIdSkpd();
        $this->hashidPegawai = $this->_hashIdPegawai();
    }

    private function _hashIdSkpd()
    {
        return new Hashids(env('SECRET_SALT_KEY'),15);
    }

    private function _hashIdPegawai()
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
        // $file->move(public_path('upload_foto'), $filenameToStore);
        Storage::disk('local')->put('/upload_foto/' . $filenameToStore, File::get($file));

        return $filenameToStore;
    }

    public function viewImage($image)
    {
        try {
            $file = storage_path('app/upload_foto/' . $image);
            return response()->file($file);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function index(Request $request)
    {
        $hashidSkpd = $this->hashidSkpd;
        $hashidPegawai = $this->hashidPegawai;
        $idSkpd = $hashidSkpd->decode($request->segment(3))[0] ?? '';
        if (!$idSkpd) return back()->with(["type" => "error", "message" => "forbidden!"]);

        // check scope of id skpd
        if (!in_array($idSkpd, getScopeIdSkpd())) return back()->with(["type" => "error", "message" => "forbidden!"]);
        
        $pegawai = Biodata::select('id_ptt','id_skpd','jenis_ptt_id','nama','niptt','tempat_lahir','thn_lahir','jk','foto')
                    ->whereAktif('Y')
                    ->with(['skpd', 'pendidikan.jenjang', 'jabatan.refJabatan', 'jenisPtt'])
                    ->whereHas('skpd', function($query) use($request) {
                        $query->where('id_skpd', 'like', $this->hashidSkpd->decode($request->segment(3))[0] . '%');
                    })
                    // ->where('id_skpd', 'like', $this->hashidSkpd->decode($request->segment(3))[0] . '%')
                    ->when($request->nama, function($query) use ($request) {
                        $query->where('nama', 'like', '%' . $request->nama . '%')
                                ->orWhere('niptt', 'like', $request->nama . '%');
                    })
                    ->orderBy('id_ptt')
                    ->paginate(12);
                
        $skpd = Skpd::whereId($idSkpd)->first(['id', 'name']);
        if (!$skpd) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

        return view('fasilitator.pegawai.index', compact('pegawai', 'skpd', 'hashidSkpd', 'hashidPegawai'));
    }

    public function show(Request $request)
    {
        $hashidSkpd = $this->hashidSkpd;
        $hashidPegawai = $this->hashidPegawai;

        $idSkpd = $hashidSkpd->decode($request->segment(3))[0] ?? '';
        if (!$idSkpd) return back()->with(["type" => "error", "message" => "forbidden!"]);

        // check scope of id skpd
        if (!in_array($idSkpd, getScopeIdSkpd())) return back()->with(["type" => "error", "message" => "forbidden!"]);

        $idPegawai = $hashidPegawai->decode($request->segment(5))[0] ?? '';
        $pegawai = Biodata::whereId_ptt($idPegawai)->whereAktif('Y')->first([
            'id_ptt',
            'id_skpd',
            'jenis_ptt_id',
            'nama',
            'niptt',
            'tempat_lahir',
            'thn_lahir',
            'jk',
            'foto',
            'nik',
            'kk',
            'no_bpjs',
            'kelas_id',
            'no_bpjs_naker',
            'jk',
            'id_agama',
            'id_kawin',
            'alamat',
            'kode_pos',
            'no_hp',
            'email'
        ]);

        if (!$pegawai) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        
        // $skpd = Skpd::select('id', 'name')->whereId(substr($pegawai->id_skpd,0,3))->first();
        $skpd = Skpd::select('id', 'name')->whereId($idSkpd)->first();

        $ref_agama = RefAgama::pluck('agama', 'id_agama');

        $ref_kawin = RefKawin::pluck('status_kawin', 'id_kawin');

        $kelas = RefKelasBpjs::pluck('kelas', 'id');

        $ref_jenis_ptt = RefJenisPtt::pluck('jenis_ptt', 'id');

        return view('fasilitator.pegawai.show', compact('skpd', 'ref_agama', 'ref_kawin', 'pegawai', 'kelas', 'ref_jenis_ptt', 'hashidSkpd', 'hashidPegawai'));
    }

    public function update(BiodataRequest $request)
    {
        DB::beginTransaction();
        try {
            $alamat = $request->alamat . '|' . $request->rt . '|' . $request->rw . '|' . $request->desa . '|' . $request->kec . '|' . $request->kab . '|' . $request->prov;
            $hashidPegawai = $this->hashidPegawai;
            $data = Biodata::whereId_ptt($hashidPegawai->decode($request->id)[0])->first();

            if ($request->hasFile('foto')) {
                if (File::exists(public_path('upload_foto/' . $data->foto)) && $data->foto != null && $data->foto != '') {
                    unlink(public_path('upload_foto/' . $data->foto));
                }
                $file = $this->_uploadFile($request->file('foto'));
            } else {
                $file = $data->foto;
            }

            if ($data) {
                $data->nama = $request->nama;
                if (auth()->user()->level == 'admin') {
                    $data->niptt = $request->niptt;
                }
                $data->nik = $request->nik;
                $data->jenis_ptt_id = $request->jenis_ptt;
                $data->kk = $request->kk;
                $data->no_bpjs = $request->no_bpjs;
                $data->kelas_id = $request->kelas;
                $data->no_bpjs_naker = $request->no_bpjs_naker;
                $data->tempat_lahir = $request->tempat_lahir;
                $data->thn_lahir = $request->thn_lahir;
                $data->jk = $request->jk;
                $data->id_agama = $request->agama;
                $data->id_kawin = $request->kawin;
                $data->alamat = $alamat;
                $data->kode_pos = $request->kode_pos;
                $data->email = $request->email;
                $data->no_hp = $request->no_hp;
                $data->id_skpd = explode(" - ", $request->skpd)[0];
                $data->foto = $file;
                $data->save();
            }

            if (strlen($data->id_skpd) > 3) {
                $es2 = Skpd::whereId(substr($data->id_skpd,0,3))->first(['name']);
                $es3 = Skpd::whereId(substr($data->id_skpd,0,5))->first(['name']);
                $biro = Skpd::whereId(substr($data->id_skpd,0,7))->first(['name']);
                $sma = Skpd::whereId(substr($data->id_skpd,0,11))->first(['name']);

                if (substr($data->id_skpd,0,3) == 101) { // biro
                    $skpd = $es2->name . ' - ' . $biro->name;
                } elseif (substr($data->id_skpd,0,3) == 105) { // dinas pendidikan
                    if (strlen($data->id_skpd) == 5) {
                        $skpd = $es2->name . ' - ' . $es3->name;
                    } elseif (strlen($data->id_skpd) >= 11) {
                        $skpd = $es2->name . ' - ' . $es3->name . ' - ' . $sma->name;
                    } else {
                        $skpd = $es2->name . ' - ' . $es3->name;
                    }
                } else {
                    $skpd = $es2->name . ' - ' . $es3->name;
                }
            } else {
                $skpd = $data->skpd->name;
            }

            // update table download
            $ref_agama = RefAgama::whereId_agama($request->agama)->first(['agama']);
            $ref_status_kawin = RefKawin::whereId_kawin($request->kawin)->first(['status_kawin']);
            $ref_jenis_ptt = RefJenisPtt::whereId($request->jenis_ptt)->first(['jenis_ptt']);
            $update = DownloadPegawai::whereId_ptt($hashidPegawai->decode($request->id)[0])->first();

            if (!$update) return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            if (auth()->user()->level == 'admin') {
                $update->niptt = $request->niptt;
            }
            $update->nama = $request->nama;
            $update->jenis_ptt = $ref_jenis_ptt->jenis_ptt;
            $update->tempat_lahir = $request->tempat_lahir;
            $update->tgl_lahir = $request->thn_lahir;
            $update->jk = $request->jk;
            $update->nik = $request->nik;
            $update->agama = $ref_agama->agama;
            $update->status_kawin = $ref_status_kawin->status_kawin;
            $update->alamat = $alamat;
            $update->kode_pos = $request->kode_pos;
            $update->no_hp = $request->no_hp;
            $update->no_bpjs = $request->no_bpjs;
            $update->kelas = $request->kelas;
            $update->no_bpjs_naker = $request->no_bpjs_naker;
            $update->id_skpd = explode(" - ", $request->skpd)[0];
            $update->unit_kerja = explode(" - ", $request->skpd)[1];
            $update->skpd = $skpd;
            $update->save();

            DB::commit();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $hashidPegawai->decode($request->id)[0], 'biodata', 'update');

            return back()->with(["type" => "success", "message" => "berhasil diupdate!"]);
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function autocomplete(Request $request)
    {
        $data = Biodata::whereAktif('Y')
                ->where('id_skpd', 'like', $request->id_skpd . '%')
                ->where(function($query) use ($request) {
                    $query->where('nama', 'like', '%' . $request->search . '%')
                        ->orWhere('niptt', 'like', $request->search . '%');
                })
                ->limit(10)
                ->orderBy('nama')
                ->get(['nama', 'niptt']);
        
        $res = [];
        foreach ($data as $value) {
            $res[] = [
                'label' => $value->nama
            ];
        }
        return response()->json($res);
    }
}
