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
                if (!$request->query('nama')) {
                    // $pegawai = DownloadPegawai::whereAktif('Y')
                    //         ->where('id_skpd', 'like', $hashid->decode($request->segment(3))[0] . '%')
                    //         ->get();
                    $pegawai = DB::table('download')
                            ->select('*', DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                            ->where('id_skpd', 'like', $hashid->decode($request->segment(3))[0] . '%')
                            ->where('aktif', 'Y')
                            ->get();
                } else {
                    // $pegawai = DownloadPegawai::whereAktif('Y')
                    //         ->where('nama', 'like', '%' . $request->query('nama') . '%')
                    //         ->orWhere('niptt', $request->query('nama'))
                    //         ->get();
                    $pegawai = DB::table('download')
                            ->select('*', DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                            ->where('aktif', 'Y')
                            ->where('nama', 'like', '%' . $request->query('nama') . '%')
                            ->orWhere('niptt', $request->query('nama'))
                            ->get();
                }
            } else {
                if (!$request->query('nama')) {
                    $pegawai = DownloadPegawai::whereAktif('Y')
                        ->where('id_skpd', 'like', $hashid->decode($request->segment(3))[0] . '%')
                        ->get()->makeHidden(['tahun_penilaian', 'rekomendasi']);
                } else {
                    $pegawai = DownloadPegawai::whereAktif('Y')
                            ->where('nama', 'like', '%' . $request->query('nama') . '%')
                            ->orWhere('niptt', $request->query('nama'))
                            ->get()->makeHidden(['tahun_penilaian', 'rekomendasi']);
                }                
            }
            return (new FastExcel($pegawai))->download('data-pegawai.xlsx');
        } catch (\Throwable $th) {
            throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadByAgama(Request $request)
    {
        try {
            $idSkpd = $request->idSkpd ?? auth()->user()->id_skpd;
            $pegawai = DownloadPegawai::select('niptt', 'nama', 'jenis_ptt', 'jk', 'agama', 'unit_kerja', 'skpd')
                    ->whereAktif('Y')
                    ->where('id_skpd', 'like', $idSkpd . '%')
                    ->get();
            return (new FastExcel($pegawai))->download('data-pegawai-by-agama.xlsx');
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadPerson(Request $request)
    {
        try {
            $hashid = $this->_hashIdPegawai();
            $pegawai = DownloadPegawai::where('id_ptt', $hashid->decode($request->segment(3))[0])->get()->makeHidden(['tahun_penilaian', 'rekomendasi']);
            return (new FastExcel($pegawai))->download("data-pegawai-{$pegawai[0]->nama}.xlsx");
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
                        ->select('*', DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();
            } else {
                // $pegawai = DownloadPegawai::whereAktif('Y')
                //             ->where('id_skpd', 'like', $idSkpd . '%')
                //             ->get()->makeHidden(['tahun_penilaian', 'rekomendasi']);
                $pegawai = DB::table('download')
                            ->select('*', DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                            ->where('id_skpd', 'like', $idSkpd . '%')
                            ->where('aktif', 'Y')
                            ->get()
                            ->map(function($peg) {
                                unset($peg->tahun_penilaian);
                                unset($peg->rekomendasi);
                                return $peg;
                            });
            }
            return (new FastExcel($pegawai))->download("data-pegawai-all.xlsx");
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
                        ->select('*', DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('jenis_ptt', 'PTT-PK')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();
            return (new FastExcel($pegawai))->download("data-pegawai-pttpk.xlsx");
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
                        ->select('*', DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('jenis_ptt', 'PTT CABDIN')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();
            return (new FastExcel($pegawai))->download("data-pegawai-ptt-cabdin.xlsx");
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
                        ->select('*', DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('jenis_ptt', 'PTT SEKOLAH')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();
            return (new FastExcel($pegawai))->download("data-pegawai-ptt-sekolah.xlsx");
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
                        ->select('*', DB::raw('timestampdiff(year, tgl_lahir, curdate()) as usia'))
                        ->where('jenis_ptt', 'GTT')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->where('aktif', 'Y')
                        ->get();
            return (new FastExcel($pegawai))->download("data-pegawai-gtt.xlsx");
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadDataAnak()
    {
        return view('fasilitator.download.data_anak');
    }

    public function downloadDataPasangan()
    {
        return view('fasilitator.download.data_pasangan');
    }
}
