<?php

namespace App\Http\Controllers\Fasilitator;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use App\Models\Skpd;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class DownloadDataKeluargaController extends Controller
{
    public function index()
    {
        $skpd = Skpd::where('id', auth()->user()->id_skpd)->first(['id', 'name']);

        return view('fasilitator.download.data_keluarga', compact('skpd'));
    }

    public function downloadPasangan(Request $request)
    {
        try {
            $idSkpd = $request->idSkpd ?? auth()->user()->id_skpd;
            $data = Biodata::join('rwyt_suami_istri', 'ptt_biodata.id_ptt', '=', 'rwyt_suami_istri.id_ptt')
                        ->join('ref_pekerjaan', 'rwyt_suami_istri.pekerjaan_id', '=', 'ref_pekerjaan.pekerjaan_id')
                        ->select(
                            'ptt_biodata.nama',
                            'ptt_biodata.niptt',
                            'nama_suami_istri',
                            'rwyt_suami_istri.no_bpjs',
                            'rwyt_suami_istri.kelas_id',
                            'rwyt_suami_istri.tempat_lahir',
                            'rwyt_suami_istri.tgl_lahir',
                            'rwyt_suami_istri.instansi',
                            'ref_pekerjaan.pekerjaan'
                        )
                        ->where('ptt_biodata.aktif', 'Y')
                        ->where('rwyt_suami_istri.aktif', 'Y')
                        ->where('ptt_biodata.id_kawin', 2)
                        ->where('ptt_biodata.id_skpd', 'like', $idSkpd . '%')
                        ->get();

            return (new FastExcel($data))->download('data-pasangan.xlsx', function($row) {
                return [
                    'Nama Pegawai' => $row->nama,
                    'NIPTT' => $row->niptt,
                    'Nama Suami/Istri' => $row->nama_suami_istri,
                    'No BPJS' => $row->no_bpjs,
                    'Kelas BPJS' => $row->kelas_id,
                    'Tempat Lahir' => $row->tempat_lahir,
                    'Tgl Lahir' => $row->tgl_lahir,
                    'Instansi' => $row->instansi,
                    'Pekerjaan' => $row->pekerjaan,
                ];
            });
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadKeluarga()
    {
        try {
            $idSkpd = request()->idSkpd ?? auth()->user()->id_skpd;
            $data = Biodata::join('setting_status_kawin', 'setting_status_kawin.id_kawin', '=', 'ptt_biodata.id_kawin')
                        ->join('rwyt_suami_istri', 'ptt_biodata.id_ptt', '=', 'rwyt_suami_istri.id_ptt')
                        ->join('rwyt_anak', 'rwyt_anak.id_ptt', '=', 'ptt_biodata.id_ptt')
                        ->join('ref_pekerjaan_anak', 'rwyt_anak.pekerjaan_anak_id', '=', 'ref_pekerjaan_anak.pekerjaan_id')
                        ->join('ref_status_anak', 'rwyt_anak.status_anak_id', '=', 'ref_status_anak.status_anak_id')
                        ->select(
                            'ptt_biodata.nama',
                            'ptt_biodata.niptt',
                            'status_kawin',
                            'nama_suami_istri',
                            'rwyt_suami_istri.tempat_lahir as tempat_lahir_pasangan',
                            'rwyt_suami_istri.tgl_lahir as tgl_lahir_pasangan',
                            'rwyt_suami_istri.no_bpjs as no_bpjs_pasangan',
                            'rwyt_suami_istri.kelas_id as kelas_bpjs_pasangan',
                            'rwyt_anak.nama as nama_anak',
                            'rwyt_anak.tempat_lahir as tempat_lahir_anak',
                            'rwyt_anak.tgl_lahir as tgl_lahir_anak',
                            'rwyt_anak.no_bpjs as no_bpjs_anak',
                            'rwyt_anak.kelas_id as kelas_bpjs_anak',
                            'status_anak',
                            'pekerjaan as pekerjaan_anak',
                        )
                        ->where('ptt_biodata.aktif', 'Y')
                        ->whereIn('ptt_biodata.id_kawin', ['2', '3', '4'])
                        ->where('ptt_biodata.id_skpd', 'like', $idSkpd . '%')
                        ->orderBy('ptt_biodata.id_ptt')
                        ->get();

            return (new FastExcel($data))->download('data-keluarga.xlsx', function($row) {
                return [
                    'Nama Pegawai' => $row->nama,
                    'NIPTT' => $row->niptt,
                    'Status Pernikahan' => $row->status_kawin,
                    'Nama Pasangan' => $row->nama_suami_istri,
                    'Tempat Lahir Pasangan' => $row->tempat_lahir_pasangan,
                    'Tgl Lahir Pasangan' => $row->tgl_lahir_pasangan,
                    'No BPJS Pasangan' => $row->no_bpjs_pasangan,
                    'Kelas BPJS Pasangan' => $row->kelas_bpjs_pasangan,
                    'Nama Anak' => $row->nama_anak,
                    'Tempat Lahir Anak' => $row->tempat_lahir_anak,
                    'Tgl Lahir Anak' => $row->tgl_lahir_anak,
                    'No BPJS Anak' => $row->no_bpjs_anak,
                    'Kelas BPJS Anak' => $row->kelas_bpjs_anak,
                    'Status Anak' => $row->status_anak,
                    'Pekerjaan Anak' => $row->pekerjaan_anak,
                ];
            });
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
