<?php

namespace App\Http\Controllers\Fasilitator;

use Hashids\Hashids;
use Illuminate\Http\Request;
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
            $pegawai = DownloadPegawai::whereAktif('Y')
                    ->where('id_skpd', 'like', $hashid->decode($request->segment(3))[0] . '%')
                    ->get();
            return (new FastExcel($pegawai))->download('data-pegawai.xlsx');
        } catch (\Throwable $th) {
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
            $pegawai = DownloadPegawai::where('id_ptt', $hashid->decode($request->segment(3))[0])->get();
            return (new FastExcel($pegawai))->download("data-pegawai-{$pegawai[0]->nama}.xlsx");
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function downloadPttpk(Request $request)
    {
        try {
            $idSkpd = $request->idSkpd ?? auth()->user()->id_skpd;
            $pegawai = DownloadPegawai::whereJenis_ptt('PTT-PK')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->whereAktif('Y')
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
            $pegawai = DownloadPegawai::whereJenis_ptt('PTT CABDIN')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->whereAktif('Y')
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
            $pegawai = DownloadPegawai::whereJenis_ptt('PTT SEKOLAH')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->whereAktif('Y')
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
            $pegawai = DownloadPegawai::whereJenis_ptt('GTT')
                        ->where('id_skpd', 'like', $idSkpd . '%')
                        ->whereAktif('Y')
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
