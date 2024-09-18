<?php

namespace App\Http\Controllers\Fasilitator;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Fasilitator\DownloadPegawai;

class DownloadPegawaiController extends Controller
{
    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),15);
    }

    private function _hashIdPegawai()
    {
        return new Hashids(env('SECRET_SALT_KEY'),10);
    }

    public function download(Request $request)
    {
        try {
            $hashid = $this->_hashId();
            if (auth()->user()->level == 'admin') {
                if (!$request->query('nama') && !$request->query('jenis_ptt')) {
                    $pegawai = DB::table('download')
                            ->select(
                                'niptt',
                                'nama',
                                'jenis_ptt',
                                'tempat_lahir',
                                'tgl_lahir',
                                'jk',
                                'nik',
                                'agama',
                                'status_kawin',
                                'alamat',
                                'kode_pos',
                                'no_hp',
                                'no_bpjs',
                                'kelas',
                                'no_bpjs_naker',
                                'jenjang',
                                'nama_sekolah',
                                'jurusan',
                                'akreditasi',
                                'thn_lulus',
                                'jabatan',
                                'no_sk',
                                'tgl_sk',
                                'tgl_mulai',
                                'tgl_akhir',
                                'id_skpd',
                                'unit_kerja',
                                'skpd',
                                'tes_narkoba',
                                'tahun_tes_narkoba',
                                'aktif',
                                DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                            ->where('id_skpd', 'like', $hashid->decode($request->segment(3))[0] . '%')
                            ->where('aktif', 'Y')
                            ->get();
                } else if ($request->query('nama') && !$request->query('jenis_ptt')) {
                    $pegawai = DB::table('download')
                            ->select(
                                'niptt',
                                'nama',
                                'jenis_ptt',
                                'tempat_lahir',
                                'tgl_lahir',
                                'jk',
                                'nik',
                                'agama',
                                'status_kawin',
                                'alamat',
                                'kode_pos',
                                'no_hp',
                                'no_bpjs',
                                'kelas',
                                'no_bpjs_naker',
                                'jenjang',
                                'nama_sekolah',
                                'jurusan',
                                'akreditasi',
                                'thn_lulus',
                                'jabatan',
                                'no_sk',
                                'tgl_sk',
                                'tgl_mulai',
                                'tgl_akhir',
                                'id_skpd',
                                'unit_kerja',
                                'skpd',
                                'tes_narkoba',
                                'tahun_tes_narkoba',
                                'aktif',
                                DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                            ->where('aktif', 'Y')
                            ->where('nama', 'like', '%' . $request->query('nama') . '%')
                            ->orWhere('niptt', $request->query('nama'))
                            ->get();
                } else if ($request->query('jenis_ptt') && !$request->query('nama')) {
                    $ref_jenis_ptt = DB::table('ref_jenis_ptt')->get();
                    foreach ($ref_jenis_ptt as $item) {
                        if ($request->jenis_ptt == $item->id) {
                            $jenis_ptt = $item->jenis_ptt;
                            break;
                        }
                    }

                    $pegawai = DB::table('download')
                            ->select(
                                'niptt',
                                'nama',
                                'jenis_ptt',
                                'tempat_lahir',
                                'tgl_lahir',
                                'jk',
                                'nik',
                                'agama',
                                'status_kawin',
                                'alamat',
                                'kode_pos',
                                'no_hp',
                                'no_bpjs',
                                'kelas',
                                'no_bpjs_naker',
                                'jenjang',
                                'nama_sekolah',
                                'jurusan',
                                'akreditasi',
                                'thn_lulus',
                                'jabatan',
                                'no_sk',
                                'tgl_sk',
                                'tgl_mulai',
                                'tgl_akhir',
                                'id_skpd',
                                'unit_kerja',
                                'skpd',
                                'tes_narkoba',
                                'tahun_tes_narkoba',
                                'aktif',
                                DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                            ->where('aktif', 'Y')
                            ->where('jenis_ptt', $jenis_ptt)
                            ->get();
                }
            } else {
                if (!$request->query('nama') && !$request->query('jenis_ptt')) {
                    // $pegawai = DownloadPegawai::whereAktif('Y')
                    //     ->where('id_skpd', 'like', $hashid->decode($request->segment(3))[0] . '%')
                    //     ->get()->makeHidden(['tahun_penilaian', 'rekomendasi']);
                    $pegawai = DB::table('download')
                        ->select(
                            'niptt',
                            'nama',
                            'jenis_ptt',
                            'tempat_lahir',
                            'tgl_lahir',
                            'jk',
                            'nik',
                            'agama',
                            'status_kawin',
                            'alamat',
                            'kode_pos',
                            'no_hp',
                            'no_bpjs',
                            'kelas',
                            'no_bpjs_naker',
                            'jenjang',
                            'nama_sekolah',
                            'jurusan',
                            'akreditasi',
                            'thn_lulus',
                            'jabatan',
                            'no_sk',
                            'tgl_sk',
                            'tgl_mulai',
                            'tgl_akhir',
                            'id_skpd',
                            'unit_kerja',
                            'skpd',
                            'tes_narkoba',
                            'tahun_tes_narkoba',
                            'aktif',
                            DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('id_skpd', 'like', $hashid->decode($request->segment(3))[0] . '%')
                        ->where('aktif', 'Y')
                        ->get()
                        ->map(function($peg) {
                            unset($peg->tahun_penilaian);
                            unset($peg->rekomendasi);
                            return $peg;
                        });
                } else if ($request->query('nama') && !$request->query('jenis_ptt')) {
                    $pegawai = DB::table('download')
                            ->select(
                                'niptt',
                                'nama',
                                'jenis_ptt',
                                'tempat_lahir',
                                'tgl_lahir',
                                'jk',
                                'nik',
                                'agama',
                                'status_kawin',
                                'alamat',
                                'kode_pos',
                                'no_hp',
                                'no_bpjs',
                                'kelas',
                                'no_bpjs_naker',
                                'jenjang',
                                'nama_sekolah',
                                'jurusan',
                                'akreditasi',
                                'thn_lulus',
                                'jabatan',
                                'no_sk',
                                'tgl_sk',
                                'tgl_mulai',
                                'tgl_akhir',
                                'id_skpd',
                                'unit_kerja',
                                'skpd',
                                'tes_narkoba',
                                'tahun_tes_narkoba',
                                'aktif',
                                DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                            ->where('id_skpd', 'like', $hashid->decode($request->segment(3))[0] . '%')
                            ->where('aktif', 'Y')
                            ->where('nama', 'like', '%' . $request->query('nama') . '%')
                            ->orWhere('niptt', $request->query('nama'))
                            ->get()
                            ->map(function($peg) {
                                unset($peg->tahun_penilaian);
                                unset($peg->rekomendasi);
                                return $peg;
                            });
                } else if ($request->query('jenis_ptt') && !$request->query('nama')) {
                    $ref_jenis_ptt = DB::table('ref_jenis_ptt')->get();
                    foreach ($ref_jenis_ptt as $item) {
                        if ($request->jenis_ptt == $item->id) {
                            $jenis_ptt = $item->jenis_ptt;
                            break;
                        }
                    }

                    $pegawai = DB::table('download')
                            ->select(
                                'niptt',
                                'nama',
                                'jenis_ptt',
                                'tempat_lahir',
                                'tgl_lahir',
                                'jk',
                                'nik',
                                'agama',
                                'status_kawin',
                                'alamat',
                                'kode_pos',
                                'no_hp',
                                'no_bpjs',
                                'kelas',
                                'no_bpjs_naker',
                                'jenjang',
                                'nama_sekolah',
                                'jurusan',
                                'akreditasi',
                                'thn_lulus',
                                'jabatan',
                                'no_sk',
                                'tgl_sk',
                                'tgl_mulai',
                                'tgl_akhir',
                                'id_skpd',
                                'unit_kerja',
                                'skpd',
                                'tes_narkoba',
                                'tahun_tes_narkoba',
                                'aktif',
                                DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                            ->where('id_skpd', 'like', $hashid->decode($request->segment(3))[0] . '%')
                            ->where('aktif', 'Y')
                            ->where('jenis_ptt', $jenis_ptt)
                            ->get()
                            ->map(function($peg) {
                                unset($peg->tahun_penilaian);
                                unset($peg->rekomendasi);
                                return $peg;
                            });
                }
            }

            return (new FastExcel($pegawai))->download('data-pegawai.xlsx', function($row) {
                return [
                    'NIPTT' => $row->niptt,
                    'Nama' => $row->nama,
                    'Jenis PTT' => $row->jenis_ptt,
                    'Tempat Lahir' => $row->tempat_lahir,
                    'Tgl Lahir' => $row->tgl_lahir,
                    'JK' => $row->jk,
                    'NIK' => $row->nik,
                    'Agama' => $row->agama,
                    'Status Pernikahan' => $row->status_kawin,
                    'Alamat' => $row->alamat,
                    'Kode Pos' => $row->kode_pos,
                    'No. HP' => $row->no_hp,
                    'No. BPJS' => $row->no_bpjs,
                    'Kelas BPJS' => $row->kelas,
                    'No. BPJS Ketenagakerjaan' => $row->no_bpjs_naker,
                    'Jenjang Pendidikan' => $row->jenjang,
                    'Nama Sekolah' => $row->nama_sekolah,
                    'Jurusan' => $row->jurusan,
                    'Akreditasi' => $row->akreditasi,
                    'Tahun Lulus' => $row->thn_lulus,
                    'Jabatan' => $row->jabatan,
                    'No. SK' => $row->no_sk,
                    'Tgl SK' => $row->tgl_sk,
                    'Tgl Mulai Kontrak' => $row->tgl_mulai,
                    'Tgl Akhir Kontrak' => $row->tgl_akhir,
                    'Kode Unit Kerja' => $row->id_skpd,
                    'Unit Kerja' => $row->unit_kerja,
                    'SKPD' => $row->skpd,
                    'Tes Narkoba' => $row->tes_narkoba,
                    'Tahun Tes Narkoba' => $row->tahun_tes_narkoba,
                    'Usia' => $row->usia,
                    'Aktif' => $row->aktif
                ];
            });
        } catch (\Throwable $th) {
            throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadByAgama(Request $request)
    {
        try {
            $idSkpd = $request->idSkpd ?? auth()->user()->id_skpd;
            $pegawai = DownloadPegawai::select('niptt', 'nama', 'jenis_ptt', 'jk', 'agama', 'id_skpd', 'unit_kerja', 'skpd')
                    ->whereAktif('Y')
                    ->where('id_skpd', 'like', $idSkpd . '%')
                    ->get();

            return (new FastExcel($pegawai))->download('data-pegawai-by-agama.xlsx', function($row) {
                return [
                    'NIPTT' => $row->niptt,
                    'Nama' => $row->nama,
                    'Jenis PTT' => $row->jenis_ptt,
                    'JK' => $row->jk,
                    'Agama' => $row->agama,
                    'Kode Unit Kerja' => $row->id_skpd,
                    'Unit Kerja' => $row->unit_kerja,
                    'SKPD' => $row->skpd,
                ];
            });
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadPerson(Request $request)
    {
        try {
            $hashid = $this->_hashIdPegawai();
            $pegawai = DownloadPegawai::
                        select(
                                'niptt',
                                'nama',
                                'jenis_ptt',
                                'tempat_lahir',
                                'tgl_lahir',
                                'jk',
                                'nik',
                                'agama',
                                'status_kawin',
                                'alamat',
                                'kode_pos',
                                'no_hp',
                                'no_bpjs',
                                'kelas',
                                'no_bpjs_naker',
                                'jenjang',
                                'nama_sekolah',
                                'jurusan',
                                'akreditasi',
                                'thn_lulus',
                                'jabatan',
                                'no_sk',
                                'tgl_sk',
                                'tgl_mulai',
                                'tgl_akhir',
                                'id_skpd',
                                'unit_kerja',
                                'skpd',
                                'tes_narkoba',
                                'tahun_tes_narkoba',
                                'aktif',
                                DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('id_ptt', $hashid->decode($request->segment(3))[0])
                        ->get();
                        // ->makeHidden(['tahun_penilaian', 'rekomendasi']);

            return (new FastExcel($pegawai))->download("data-pegawai-{$pegawai[0]->nama}.xlsx", function($row) {
                return [
                    'NIPTT' => $row->niptt,
                    'Nama' => $row->nama,
                    'Jenis PTT' => $row->jenis_ptt,
                    'Tempat Lahir' => $row->tempat_lahir,
                    'Tgl Lahir' => $row->tgl_lahir,
                    'JK' => $row->jk,
                    'NIK' => $row->nik,
                    'Agama' => $row->agama,
                    'Status Pernikahan' => $row->status_kawin,
                    'Alamat' => $row->alamat,
                    'Kode Pos' => $row->kode_pos,
                    'No. HP' => $row->no_hp,
                    'No. BPJS' => $row->no_bpjs,
                    'Kelas BPJS' => $row->kelas,
                    'No. BPJS Ketenagakerjaan' => $row->no_bpjs_naker,
                    'Jenjang Pendidikan' => $row->jenjang,
                    'Nama Sekolah' => $row->nama_sekolah,
                    'Jurusan' => $row->jurusan,
                    'Akreditasi' => $row->akreditasi,
                    'Tahun Lulus' => $row->thn_lulus,
                    'Jabatan' => $row->jabatan,
                    'No. SK' => $row->no_sk,
                    'Tgl SK' => $row->tgl_sk,
                    'Tgl Mulai Kontrak' => $row->tgl_mulai,
                    'Tgl Akhir Kontrak' => $row->tgl_akhir,
                    'Kode Unit Kerja' => $row->id_skpd,
                    'Unit Kerja' => $row->unit_kerja,
                    'SKPD' => $row->skpd,
                    'Tes Narkoba' => $row->tes_narkoba,
                    'Tahun Tes Narkoba' => $row->tahun_tes_narkoba,
                    'Usia' => $row->usia,
                    'Aktif' => $row->aktif
                ];
            });
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadPegawai(Request $request)
    {
        try {
            $idSkpd = $request->idSkpd ?? auth()->user()->id_skpd;
            if (auth()->user()->level == 'admin') {
                // $pegawai = DownloadPegawai::whereAktif('Y')
                //             ->where('id_skpd', 'like', $idSkpd . '%')
                //             ->get();                
                $pegawai = DB::table('download')
                        ->select(
                            'niptt',
                            'nama',
                            'jenis_ptt',
                            'tempat_lahir',
                            'tgl_lahir',
                            'jk',
                            'nik',
                            'agama',
                            'status_kawin',
                            'alamat',
                            'kode_pos',
                            'no_hp',
                            'no_bpjs',
                            'kelas',
                            'no_bpjs_naker',
                            'jenjang',
                            'nama_sekolah',
                            'jurusan',
                            'akreditasi',
                            'thn_lulus',
                            'jabatan',
                            'no_sk',
                            'tgl_sk',
                            'tgl_mulai',
                            'tgl_akhir',
                            'unit_kerja',
                            'skpd',
                            'tes_narkoba',
                            'tahun_tes_narkoba',
                            'aktif',
                            DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();
            } else {
                // $pegawai = DownloadPegawai::whereAktif('Y')
                //             ->where('id_skpd', 'like', $idSkpd . '%')
                //             ->get()->makeHidden(['tahun_penilaian', 'rekomendasi']);
                $pegawai = DB::table('download')
                            ->select(
                                'niptt',
                                'nama',
                                'jenis_ptt',
                                'tempat_lahir',
                                'tgl_lahir',
                                'jk',
                                'nik',
                                'agama',
                                'status_kawin',
                                'alamat',
                                'kode_pos',
                                'no_hp',
                                'no_bpjs',
                                'kelas',
                                'no_bpjs_naker',
                                'jenjang',
                                'nama_sekolah',
                                'jurusan',
                                'akreditasi',
                                'thn_lulus',
                                'jabatan',
                                'no_sk',
                                'tgl_sk',
                                'tgl_mulai',
                                'tgl_akhir',
                                'id_skpd',
                                'unit_kerja',
                                'skpd',
                                'tes_narkoba',
                                'tahun_tes_narkoba',
                                'aktif',
                                DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                            ->where('id_skpd', 'like', $idSkpd . '%')
                            ->where('aktif', 'Y')
                            ->get()
                            ->map(function($peg) {
                                unset($peg->tahun_penilaian);
                                unset($peg->rekomendasi);
                                return $peg;
                            });
            }
            return (new FastExcel($pegawai))->download("data-pegawai-all.xlsx", function($row) {
                return [
                    'NIPTT' => $row->niptt,
                    'Nama' => $row->nama,
                    'Jenis PTT' => $row->jenis_ptt,
                    'Tempat Lahir' => $row->tempat_lahir,
                    'Tgl Lahir' => $row->tgl_lahir,
                    'JK' => $row->jk,
                    'NIK' => $row->nik,
                    'Agama' => $row->agama,
                    'Status Pernikahan' => $row->status_kawin,
                    'Alamat' => $row->alamat,
                    'Kode Pos' => $row->kode_pos,
                    'No. HP' => $row->no_hp,
                    'No. BPJS' => $row->no_bpjs,
                    'Kelas BPJS' => $row->kelas,
                    'No. BPJS Ketenagakerjaan' => $row->no_bpjs_naker,
                    'Jenjang Pendidikan' => $row->jenjang,
                    'Nama Sekolah' => $row->nama_sekolah,
                    'Jurusan' => $row->jurusan,
                    'Akreditasi' => $row->akreditasi,
                    'Tahun Lulus' => $row->thn_lulus,
                    'Jabatan' => $row->jabatan,
                    'No. SK' => $row->no_sk,
                    'Tgl SK' => $row->tgl_sk,
                    'Tgl Mulai Kontrak' => $row->tgl_mulai,
                    'Tgl Akhir Kontrak' => $row->tgl_akhir,
                    'Kode Unit Kerja' => $row->id_skpd,
                    'Unit Kerja' => $row->unit_kerja,
                    'SKPD' => $row->skpd,
                    'Tes Narkoba' => $row->tes_narkoba,
                    'Tahun Tes Narkoba' => $row->tahun_tes_narkoba,
                    'Usia' => $row->usia,
                    'Aktif' => $row->aktif
                ];
            });
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
    
    public function downloadPttpk(Request $request)
    {
        try {
            $idSkpd = $request->idSkpd ?? auth()->user()->id_skpd;
            // $pegawai = DownloadPegawai::whereJenis_ptt('PTT-PK')
            //             ->where('id_skpd', 'like', $idSkpd . '%')
            //             ->whereAktif('Y')
            //             ->get();
            $pegawai = DB::table('download')
                        ->select(
                            'niptt',
                            'nama',
                            'jenis_ptt',
                            'tempat_lahir',
                            'tgl_lahir',
                            'jk',
                            'nik',
                            'agama',
                            'status_kawin',
                            'alamat',
                            'kode_pos',
                            'no_hp',
                            'no_bpjs',
                            'kelas',
                            'no_bpjs_naker',
                            'jenjang',
                            'nama_sekolah',
                            'jurusan',
                            'akreditasi',
                            'thn_lulus',
                            'jabatan',
                            'no_sk',
                            'tgl_sk',
                            'tgl_mulai',
                            'tgl_akhir',
                            'id_skpd',
                            'unit_kerja',
                            'skpd',
                            'tes_narkoba',
                            'tahun_tes_narkoba',
                            'aktif',
                            DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('jenis_ptt', 'PTT-PK')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();

            return (new FastExcel($pegawai))->download("data-pegawai-pttpk.xlsx", function($row) {
                return [
                    'NIPTT' => $row->niptt,
                    'Nama' => $row->nama,
                    'Jenis PTT' => $row->jenis_ptt,
                    'Tempat Lahir' => $row->tempat_lahir,
                    'Tgl Lahir' => $row->tgl_lahir,
                    'JK' => $row->jk,
                    'NIK' => $row->nik,
                    'Agama' => $row->agama,
                    'Status Pernikahan' => $row->status_kawin,
                    'Alamat' => $row->alamat,
                    'Kode Pos' => $row->kode_pos,
                    'No. HP' => $row->no_hp,
                    'No. BPJS' => $row->no_bpjs,
                    'Kelas BPJS' => $row->kelas,
                    'No. BPJS Ketenagakerjaan' => $row->no_bpjs_naker,
                    'Jenjang Pendidikan' => $row->jenjang,
                    'Nama Sekolah' => $row->nama_sekolah,
                    'Jurusan' => $row->jurusan,
                    'Akreditasi' => $row->akreditasi,
                    'Tahun Lulus' => $row->thn_lulus,
                    'Jabatan' => $row->jabatan,
                    'No. SK' => $row->no_sk,
                    'Tgl SK' => $row->tgl_sk,
                    'Tgl Mulai Kontrak' => $row->tgl_mulai,
                    'Tgl Akhir Kontrak' => $row->tgl_akhir,
                    'Kode Unit Kerja' => $row->id_skpd,
                    'Unit Kerja' => $row->unit_kerja,
                    'SKPD' => $row->skpd,
                    'Tes Narkoba' => $row->tes_narkoba,
                    'Tahun Tes Narkoba' => $row->tahun_tes_narkoba,
                    'Usia' => $row->usia,
                    'Aktif' => $row->aktif
                ];
            });
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadPttCabdin(Request $request)
    {
        try {
            $idSkpd = $request->idSkpd ?? auth()->user()->id_skpd;
            // $pegawai = DownloadPegawai::whereJenis_ptt('PTT CABDIN')
            //             ->where('id_skpd', 'like', $idSkpd . '%')
            //             ->whereAktif('Y')
            //             ->get();
            $pegawai = DB::table('download')
                        ->select(
                            'niptt',
                            'nama',
                            'jenis_ptt',
                            'tempat_lahir',
                            'tgl_lahir',
                            'jk',
                            'nik',
                            'agama',
                            'status_kawin',
                            'alamat',
                            'kode_pos',
                            'no_hp',
                            'no_bpjs',
                            'kelas',
                            'no_bpjs_naker',
                            'jenjang',
                            'nama_sekolah',
                            'jurusan',
                            'akreditasi',
                            'thn_lulus',
                            'jabatan',
                            'no_sk',
                            'tgl_sk',
                            'tgl_mulai',
                            'tgl_akhir',
                            'id_skpd',
                            'unit_kerja',
                            'skpd',
                            'tes_narkoba',
                            'tahun_tes_narkoba',
                            'aktif',
                            DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('jenis_ptt', 'PTT CABDIN')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();

            return (new FastExcel($pegawai))->download("data-pegawai-ptt-cabdin.xlsx", function($row) {
                return [
                    'NIPTT' => $row->niptt,
                    'Nama' => $row->nama,
                    'Jenis PTT' => $row->jenis_ptt,
                    'Tempat Lahir' => $row->tempat_lahir,
                    'Tgl Lahir' => $row->tgl_lahir,
                    'JK' => $row->jk,
                    'NIK' => $row->nik,
                    'Agama' => $row->agama,
                    'Status Pernikahan' => $row->status_kawin,
                    'Alamat' => $row->alamat,
                    'Kode Pos' => $row->kode_pos,
                    'No. HP' => $row->no_hp,
                    'No. BPJS' => $row->no_bpjs,
                    'Kelas BPJS' => $row->kelas,
                    'No. BPJS Ketenagakerjaan' => $row->no_bpjs_naker,
                    'Jenjang Pendidikan' => $row->jenjang,
                    'Nama Sekolah' => $row->nama_sekolah,
                    'Jurusan' => $row->jurusan,
                    'Akreditasi' => $row->akreditasi,
                    'Tahun Lulus' => $row->thn_lulus,
                    'Jabatan' => $row->jabatan,
                    'No. SK' => $row->no_sk,
                    'Tgl SK' => $row->tgl_sk,
                    'Tgl Mulai Kontrak' => $row->tgl_mulai,
                    'Tgl Akhir Kontrak' => $row->tgl_akhir,
                    'Kode Unit Kerja' => $row->id_skpd,
                    'Unit Kerja' => $row->unit_kerja,
                    'SKPD' => $row->skpd,
                    'Tes Narkoba' => $row->tes_narkoba,
                    'Tahun Tes Narkoba' => $row->tahun_tes_narkoba,
                    'Usia' => $row->usia,
                    'Aktif' => $row->aktif
                ];
            });
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadPttSekolah(Request $request)
    {
        try {
            $idSkpd = $request->idSkpd ?? auth()->user()->id_skpd;
            // $pegawai = DownloadPegawai::whereJenis_ptt('PTT SEKOLAH')
            //             ->where('id_skpd', 'like', $idSkpd . '%')
            //             ->whereAktif('Y')
            //             ->get();
            $pegawai = DB::table('download')
                        ->select(
                            'niptt',
                            'nama',
                            'jenis_ptt',
                            'tempat_lahir',
                            'tgl_lahir',
                            'jk',
                            'nik',
                            'agama',
                            'status_kawin',
                            'alamat',
                            'kode_pos',
                            'no_hp',
                            'no_bpjs',
                            'kelas',
                            'no_bpjs_naker',
                            'jenjang',
                            'nama_sekolah',
                            'jurusan',
                            'akreditasi',
                            'thn_lulus',
                            'jabatan',
                            'no_sk',
                            'tgl_sk',
                            'tgl_mulai',
                            'tgl_akhir',
                            'id_skpd',
                            'unit_kerja',
                            'skpd',
                            'tes_narkoba',
                            'tahun_tes_narkoba',
                            'aktif',
                            DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('jenis_ptt', 'PTT SEKOLAH')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();

            return (new FastExcel($pegawai))->download("data-pegawai-ptt-sekolah.xlsx", function($row) {
                return [
                    'NIPTT' => $row->niptt,
                    'Nama' => $row->nama,
                    'Jenis PTT' => $row->jenis_ptt,
                    'Tempat Lahir' => $row->tempat_lahir,
                    'Tgl Lahir' => $row->tgl_lahir,
                    'JK' => $row->jk,
                    'NIK' => $row->nik,
                    'Agama' => $row->agama,
                    'Status Pernikahan' => $row->status_kawin,
                    'Alamat' => $row->alamat,
                    'Kode Pos' => $row->kode_pos,
                    'No. HP' => $row->no_hp,
                    'No. BPJS' => $row->no_bpjs,
                    'Kelas BPJS' => $row->kelas,
                    'No. BPJS Ketenagakerjaan' => $row->no_bpjs_naker,
                    'Jenjang Pendidikan' => $row->jenjang,
                    'Nama Sekolah' => $row->nama_sekolah,
                    'Jurusan' => $row->jurusan,
                    'Akreditasi' => $row->akreditasi,
                    'Tahun Lulus' => $row->thn_lulus,
                    'Jabatan' => $row->jabatan,
                    'No. SK' => $row->no_sk,
                    'Tgl SK' => $row->tgl_sk,
                    'Tgl Mulai Kontrak' => $row->tgl_mulai,
                    'Tgl Akhir Kontrak' => $row->tgl_akhir,
                    'Kode Unit Kerja' => $row->id_skpd,
                    'Unit Kerja' => $row->unit_kerja,
                    'SKPD' => $row->skpd,
                    'Tes Narkoba' => $row->tes_narkoba,
                    'Tahun Tes Narkoba' => $row->tahun_tes_narkoba,
                    'Usia' => $row->usia,
                    'Aktif' => $row->aktif
                ];
            });
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadGtt(Request $request)
    {
        try {
            $idSkpd = $request->idSkpd ?? auth()->user()->id_skpd;
            // $pegawai = DownloadPegawai::whereJenis_ptt('GTT')
            //             ->where('id_skpd', 'like', $idSkpd . '%')
            //             ->whereAktif('Y')
            //             ->get();
            $pegawai = DB::table('download')
                        ->select(
                            'niptt',
                            'nama',
                            'jenis_ptt',
                            'tempat_lahir',
                            'tgl_lahir',
                            'jk',
                            'nik',
                            'agama',
                            'status_kawin',
                            'alamat',
                            'kode_pos',
                            'no_hp',
                            'no_bpjs',
                            'kelas',
                            'no_bpjs_naker',
                            'jenjang',
                            'nama_sekolah',
                            'jurusan',
                            'akreditasi',
                            'thn_lulus',
                            'jabatan',
                            'no_sk',
                            'tgl_sk',
                            'tgl_mulai',
                            'tgl_akhir',
                            'id_skpd',
                            'unit_kerja',
                            'skpd',
                            'tes_narkoba',
                            'tahun_tes_narkoba',
                            'aktif',
                            DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('jenis_ptt', 'GTT')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();
                        
            return (new FastExcel($pegawai))->download("data-pegawai-gtt.xlsx", function($row) {
                return [
                    'NIPTT' => $row->niptt,
                    'Nama' => $row->nama,
                    'Jenis PTT' => $row->jenis_ptt,
                    'Tempat Lahir' => $row->tempat_lahir,
                    'Tgl Lahir' => $row->tgl_lahir,
                    'JK' => $row->jk,
                    'NIK' => $row->nik,
                    'Agama' => $row->agama,
                    'Status Pernikahan' => $row->status_kawin,
                    'Alamat' => $row->alamat,
                    'Kode Pos' => $row->kode_pos,
                    'No. HP' => $row->no_hp,
                    'No. BPJS' => $row->no_bpjs,
                    'Kelas BPJS' => $row->kelas,
                    'No. BPJS Ketenagakerjaan' => $row->no_bpjs_naker,
                    'Jenjang Pendidikan' => $row->jenjang,
                    'Nama Sekolah' => $row->nama_sekolah,
                    'Jurusan' => $row->jurusan,
                    'Akreditasi' => $row->akreditasi,
                    'Tahun Lulus' => $row->thn_lulus,
                    'Jabatan' => $row->jabatan,
                    'No. SK' => $row->no_sk,
                    'Tgl SK' => $row->tgl_sk,
                    'Tgl Mulai Kontrak' => $row->tgl_mulai,
                    'Tgl Akhir Kontrak' => $row->tgl_akhir,
                    'Kode Unit Kerja' => $row->id_skpd,
                    'Unit Kerja' => $row->unit_kerja,
                    'SKPD' => $row->skpd,
                    'Tes Narkoba' => $row->tes_narkoba,
                    'Tahun Tes Narkoba' => $row->tahun_tes_narkoba,
                    'Usia' => $row->usia,
                    'Aktif' => $row->aktif
                ];
            });
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadNonPtt(Request $request)
    {
        try {
            $idSkpd = $request->idSkpd ?? auth()->user()->id_skpd;
            $pegawai = DB::table('download')
                        ->select(
                            'niptt',
                            'nama',
                            'jenis_ptt',
                            'tempat_lahir',
                            'tgl_lahir',
                            'jk',
                            'nik',
                            'agama',
                            'status_kawin',
                            'alamat',
                            'kode_pos',
                            'no_hp',
                            'no_bpjs',
                            'kelas',
                            'no_bpjs_naker',
                            'jenjang',
                            'nama_sekolah',
                            'jurusan',
                            'akreditasi',
                            'thn_lulus',
                            'jabatan',
                            'no_sk',
                            'tgl_sk',
                            'tgl_mulai',
                            'tgl_akhir',
                            'id_skpd',
                            'unit_kerja',
                            'skpd',
                            'tes_narkoba',
                            'tahun_tes_narkoba',
                            'aktif',
                            DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('jenis_ptt', 'Non ASN Non PTT')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();
                        
            return (new FastExcel($pegawai))->download("data-pegawai-nonasn_nonptt.xlsx", function($row) {
                return [
                    'NIPTT' => $row->niptt,
                    'Nama' => $row->nama,
                    'Jenis PTT' => $row->jenis_ptt,
                    'Tempat Lahir' => $row->tempat_lahir,
                    'Tgl Lahir' => $row->tgl_lahir,
                    'JK' => $row->jk,
                    'NIK' => $row->nik,
                    'Agama' => $row->agama,
                    'Status Pernikahan' => $row->status_kawin,
                    'Alamat' => $row->alamat,
                    'Kode Pos' => $row->kode_pos,
                    'No. HP' => $row->no_hp,
                    'No. BPJS' => $row->no_bpjs,
                    'Kelas BPJS' => $row->kelas,
                    'No. BPJS Ketenagakerjaan' => $row->no_bpjs_naker,
                    'Jenjang Pendidikan' => $row->jenjang,
                    'Nama Sekolah' => $row->nama_sekolah,
                    'Jurusan' => $row->jurusan,
                    'Akreditasi' => $row->akreditasi,
                    'Tahun Lulus' => $row->thn_lulus,
                    'Jabatan' => $row->jabatan,
                    'No. SK' => $row->no_sk,
                    'Tgl SK' => $row->tgl_sk,
                    'Tgl Mulai Kontrak' => $row->tgl_mulai,
                    'Tgl Akhir Kontrak' => $row->tgl_akhir,
                    'Kode Unit Kerja' => $row->id_skpd,
                    'Unit Kerja' => $row->unit_kerja,
                    'SKPD' => $row->skpd,
                    'Tes Narkoba' => $row->tes_narkoba,
                    'Tahun Tes Narkoba' => $row->tahun_tes_narkoba,
                    'Usia' => $row->usia,
                    'Aktif' => $row->aktif
                ];
            });
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
