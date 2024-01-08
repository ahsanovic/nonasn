<?php

namespace App\Http\Controllers\NonAsn;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\NonAsn\HistoryPppk;
use App\Models\NonAsn\PesertaPppk;
use App\Models\NonAsn\UjianTeknis;
use App\Http\Controllers\Controller;
use App\Models\NonAsn\UjianMansoskul;
use App\Models\NonAsn\UjianWawancara;
use App\Models\NonAsn\HasilUjianTeknis;
use GuzzleHttp\Exception\ClientException;
use App\Models\NonAsn\HasilUjianMansoskul;
use App\Models\NonAsn\HasilUjianWawancara;

class NonasnSimulasiPppkController extends Controller
{
    public function index()
    {
        $client = new Client(['verify' => false]);
        try {
            $fetch = $client->get('https://apps2.bkd.jatimprov.go.id/cat-pppk/api/jabatan');
            $response = json_decode($fetch->getBody());

            $user = PesertaPppk::whereId_ptt(auth()->user()->id_ptt)->first(['id_ptt']);
            if (!$user) {
                PesertaPppk::create([
                    'id_ptt' => auth()->user()->id_ptt,
                ]);
            }

            $jabatan = PesertaPppk::whereId_ptt(auth()->user()->id_ptt)->first(['jabatan_simulasi_id', 'jabatan']);
            $history = HistoryPppk::whereId_ptt(auth()->user()->id_ptt)->paginate(10);

            return view('nonasn.simulasi.pppk.index', compact('response', 'jabatan', 'history'));
        } catch (\Throwable $th) {
            // throw $th;
            abort(500);
        }
    }

    public function updateJabatan(Request $request)
    {
        try {
            PesertaPppk::whereId_ptt(auth()->user()->id_ptt)
                    ->update([
                        'jabatan_simulasi_id' => $request->jabatan_id,
                        'jabatan' => $request->jabatan
                    ]);
    
            $data = PesertaPppk::whereId_ptt(auth()->user()->id_ptt)->first(['jabatan_simulasi_id', 'jabatan']);
    
            return response()->json([
                'status' => 'success',
                'msg' => 'ganti jabatan berhasil',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'msg' => 'terjadi kesalahan'
            ]);
        }
    }

    public function storeTeknis(Request $request)
    {
        $count = UjianTeknis::where('id_ptt', auth()->user()->id_ptt)->count();
        if ($count > 0) return redirect('simulasi-pppk/ujian-teknis/1');

        $client = new Client([ 'verify' => false ]);

        try {
            $fetch = $client->get('https://apps2.bkd.jatimprov.go.id/cat-pppk/api/soal-teknis/' . $request->jabatan_id);
            $response = json_decode($fetch->getBody());

            for ($i = 0; $i < 90 ; $i++) {
                $jawaban_kosong[$i] = '0';
            }
    
            $jawaban_kosong = implode(',', $jawaban_kosong);
            
            $ujian = new UjianTeknis();
            $ujian->id_ptt = auth()->user()->id_ptt;
            $ujian->id_jabatan = $response->data->jabatan_id;
            $ujian->id_soal = $response->data->soal_id;
            $ujian->soal = $response->data->deskripsi;
            $ujian->opsi1 = $response->data->opsi_a;
            $ujian->opsi2 = $response->data->opsi_b;
            $ujian->opsi3 = $response->data->opsi_c;
            $ujian->opsi4 = $response->data->opsi_d;
            $ujian->opsi5 = $response->data->opsi_e;
            $ujian->kunci = $response->data->kunci;
            $ujian->jawaban = $jawaban_kosong;
            $ujian->nilai_total = 0;
            $ujian->save();
    
            return redirect('simulasi-pppk/ujian-teknis/1');
        } catch (ClientException $e) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function showTeknis($id)
    {
        $count = UjianTeknis::where('id_ptt', auth()->user()->id_ptt)->count();
        if ($count < 1 OR $id < 1 OR $id > 90) {
            return redirect('ujian-teknis/1');
        }

        $fetch_data = UjianTeknis::where('id_ptt', auth()->user()->id_ptt)->first();
        $id_ujian = $fetch_data->id;
        $jawaban_user = explode(",", $fetch_data->jawaban);

        $soal = explode("|", $fetch_data->soal);
        $soal = $soal[$id-1];
        $opsi1 = explode("|", $fetch_data->opsi1);
        $opsi1 = $opsi1[$id-1];
        $opsi2 = explode("|", $fetch_data->opsi2);
        $opsi2 = $opsi2[$id-1];
        $opsi3 = explode("|", $fetch_data->opsi3);
        $opsi3 = $opsi3[$id-1];
        $opsi4 = explode("|", $fetch_data->opsi4);
        $opsi4 = $opsi4[$id-1];
        $opsi5 = explode("|", $fetch_data->opsi5);
        $opsi5 = $opsi5[$id-1];

        $waktu = $fetch_data->created_at->timestamp;

        // Count how much the empty answer
        for ($i = 0, $j = 0; $i < 90; $i++) { 
          if ($jawaban_user[$i] == '0') {
                $j++;
            }
        }

        return view('nonasn.simulasi.pppk.ujian-teknis',compact('soal','opsi1','opsi2','opsi3','opsi4','opsi5'))
                ->with('id_ujian', $id_ujian)
                ->with('nomor_sekarang', $id)
                ->with('jawaban', $jawaban_user)
                ->with('waktu', $waktu)
                ->with('jawaban_kosong', $j);
    }

    public function updateTeknis(Request $request, $id)
    {
        $fetch_data = UjianTeknis::where('id_ptt', auth()->user()->id_ptt)->first(['jawaban']);

        $jawaban_user = explode(',', $fetch_data->jawaban);

        // Replace value of current number "jawaban user" array with user answer on page
        if ($request->input('opsi') == '') {
            $jawaban_user[$id - 1] = 0;
        } else {
            $jawaban_user[$id - 1] = $request->input('opsi');
        }

        // Re-Convert array of "jawaban" into string separated by comma
        $jawaban_user = implode(',', $jawaban_user);

        // Update string of "jawaban" into ujians table in DB
        $update = UjianTeknis::where('id_ptt', auth()->user()->id_ptt)->first();
        $update->jawaban = $jawaban_user;
        $update->save();

        // Get user ujian data
        $ujian = UjianTeknis::where('id_ptt', '=', auth()->user()->id_ptt)->first();

        // Convert string separated by comma of "jawaban" column into array
        $jawaban = explode(',', $ujian->jawaban);

        // Convert string separated by comma of "kunci" column into array
        $kunci = explode(',', $ujian->kunci);

        // Initiate nilai counter
        $nilai_ujian = 0;

        // Compare user "jawaban" with "kunci"
        for ($i = 0; $i < 90; $i++) {
            if ($jawaban[$i] == $kunci[$i]) {
                $nilai_ujian += 5;
            }
        }

        // Insert user nilai into Hasil table in DB
        $nilai = UjianTeknis::where('id_ptt', auth()->user()->id_ptt)->first();
        $nilai->nilai_total = $nilai_ujian;
        $nilai->save();

        if ($id == 90) return back();

        return redirect('simulasi-pppk/ujian-teknis/' . ($id + 1));
    }

    public function destroyTeknis($id)
    {
        $count = UjianTeknis::where('id', $id)->count();        
        if ($count == 0) return redirect('simulasi-pppk/ujian-teknis/hasil');

        // Get user ujian data
        $ujian = UjianTeknis::where('id', $id)->first();

        // move nilai from table ujian to table hasil
        $hasil = new HasilUjianTeknis;
        $hasil->id_ptt = $ujian->id_ptt;
        $hasil->id_ujian = $ujian->id;
        $hasil->nilai_total = $ujian->nilai_total;
        $hasil->save();

        // move nilai from table ujian to table history
        $history = new HistoryPppk();
        $history->id_ptt = $ujian->id_ptt;
        $history->jenis_tes = 'Kompetensi Teknis';
        $history->nilai = $hasil->nilai_total;
        $history->soal = $ujian->soal;
        $history->opsi1 = $ujian->opsi1;
        $history->opsi2 = $ujian->opsi2;
        $history->opsi3 = $ujian->opsi3;
        $history->opsi4 = $ujian->opsi4;
        $history->opsi5 = $ujian->opsi5;
        $history->jawaban = $ujian->jawaban;
        $history->kunci = $ujian->kunci;
        $history->save();

        // Delete ujian record
        $ujian->delete();
        
        return redirect()->route('nonasn.simulasi.pppk.hasil-teknis');
    }

    public function hasilTeknis()
    {
        $jabatan = PesertaPppk::whereId_ptt(auth()->user()->id_ptt)->first(['jabatan_simulasi_id']);
        $hasil = HasilUjianTeknis::whereId_ptt(auth()->user()->id_ptt)->latest('id')->first();

        return view('nonasn.simulasi.pppk.hasil-teknis', compact('hasil', 'jabatan'));
    }

    public function storeWawancara()
    {
        $count = UjianWawancara::where('id_ptt', auth()->user()->id_ptt)->count();
        if ($count > 0) return redirect('simulasi-pppk/ujian-wawancara/1');

        $client = new Client([ 'verify' => false ]);

        try {
            $fetch = $client->get('https://apps2.bkd.jatimprov.go.id/cat-pppk/api/soal-wawancara');
            $response = json_decode($fetch->getBody());

            for ($i = 0; $i < 10 ; $i++) {
                $jawaban_kosong[$i] = '0';
            }
    
            $jawaban_kosong = implode(',', $jawaban_kosong);
            
            $ujian = new UjianWawancara();
            $ujian->id_ptt = auth()->user()->id_ptt;
            $ujian->id_soal = $response->data->soal_id;
            $ujian->soal = $response->data->deskripsi;
            $ujian->opsi1 = $response->data->opsi_a;
            $ujian->opsi2 = $response->data->opsi_b;
            $ujian->opsi3 = $response->data->opsi_c;
            $ujian->opsi4 = $response->data->opsi_d;
            $ujian->jawaban = $jawaban_kosong;
            $ujian->kunci = $response->data->kunci;
            $ujian->nilai_total = 0;
            $ujian->save();
    
            return redirect('simulasi-pppk/ujian-wawancara/1');
        } catch (ClientException $e) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function showWawancara($id)
    {
        $count = UjianWawancara::where('id_ptt', auth()->user()->id_ptt)->count();
        if ($count < 1 OR $id < 1 OR $id > 10) {
            return redirect('ujian-wawancara/1');
        }

        $fetch_data = UjianWawancara::where('id_ptt', auth()->user()->id_ptt)->first();
        $id_ujian = $fetch_data->id;
        $jawaban_user = explode(",", $fetch_data->jawaban);

        $soal = explode("|", $fetch_data->soal);
        $soal = $soal[$id-1];
        $opsi1 = explode("|", $fetch_data->opsi1);
        $opsi1 = $opsi1[$id-1];
        $opsi2 = explode("|", $fetch_data->opsi2);
        $opsi2 = $opsi2[$id-1];
        $opsi3 = explode("|", $fetch_data->opsi3);
        $opsi3 = $opsi3[$id-1];
        $opsi4 = explode("|", $fetch_data->opsi4);
        $opsi4 = $opsi4[$id-1];

        $waktu = $fetch_data->created_at->timestamp;

        // Count how much the empty answer
        for ($i = 0, $j = 0; $i < 10; $i++) { 
          if ($jawaban_user[$i] == '0') {
                $j++;
            }
        }

        return view('nonasn.simulasi.pppk.ujian-wawancara',compact('soal','opsi1','opsi2','opsi3','opsi4'))
                ->with('id_ujian', $id_ujian)
                ->with('nomor_sekarang', $id)
                ->with('jawaban', $jawaban_user)
                ->with('waktu', $waktu)
                ->with('jawaban_kosong', $j);
    }

    public function updateWawancara(Request $request, $id)
    {
        $fetch_data = UjianWawancara::where('id_ptt', auth()->user()->id_ptt)->first(['jawaban']);

        $jawaban_user = explode(',', $fetch_data->jawaban);

        // Replace value of current number "jawaban user" array with user answer on page
        if ($request->input('opsi') == '') {
            $jawaban_user[$id - 1] = 0;
        } else {
            $jawaban_user[$id - 1] = $request->input('opsi');
        }

        // Re-Convert array of "jawaban" into string separated by comma
        $jawaban_user = implode(',', $jawaban_user);

        // Update string of "jawaban" into ujians table in DB
        $update = UjianWawancara::where('id_ptt', auth()->user()->id_ptt)->first();
        $update->jawaban = $jawaban_user;
        $update->save();

        // Get user ujian data
        $ujian = UjianWawancara::where('id_ptt', '=', auth()->user()->id_ptt)->first();

        // Convert string separated by comma of "jawaban" column into array
        $jawaban = explode(',', $ujian->jawaban);

        // Convert string separated by comma of "kunci" column into array
        $kunci = explode(',', $ujian->kunci);

        // Initiate nilai counter
        $nilai_wawancara = 0;

        // Compare user "jawaban" with "kunci"
        for ($i = 0; $i < 10; $i++) {
            $kunci_wawancara = $kunci[$i];
            for ($j = 0; $j < 4; $j++) {
                if ($jawaban[$i] == $kunci_wawancara[$j]) {
                    if ($j == 0) {
                        $nilai_wawancara += 4;
                    } else if ($j == 1) {
                        $nilai_wawancara += 3;
                    } else if ($j == 2) {
                        $nilai_wawancara += 2;
                    } else if ($j == 3) {
                        $nilai_wawancara += 1;
                    }
                }
            }
        }

        // Insert user nilai into Hasil table in DB
        $nilai = UjianWawancara::where('id_ptt', auth()->user()->id_ptt)->first();
        $nilai->nilai_total = $nilai_wawancara;
        $nilai->save();

        if ($id == 10) return back();

        return redirect('simulasi-pppk/ujian-wawancara/' . ($id + 1));
    }

    public function destroyWawancara($id)
    {
        $count = UjianWawancara::where('id', $id)->count();        
        if ($count == 0) return redirect('simulasi-pppk/ujian-wawancara/hasil');

        // Get user ujian data
        $ujian = UjianWawancara::where('id', $id)->first();

        // move nilai from table ujian to table hasil
        $hasil = new HasilUjianWawancara;
        $hasil->id_ptt = $ujian->id_ptt;
        $hasil->id_ujian = $ujian->id;
        $hasil->nilai_total = $ujian->nilai_total;
        $hasil->save();

        // move nilai from table ujian to table history
        $history = new HistoryPppk();
        $history->id_ptt = $ujian->id_ptt;
        $history->jenis_tes = 'Wawancara';
        $history->nilai = $hasil->nilai_total;
        $history->soal = $ujian->soal;
        $history->opsi1 = $ujian->opsi1;
        $history->opsi2 = $ujian->opsi2;
        $history->opsi3 = $ujian->opsi3;
        $history->opsi4 = $ujian->opsi4;
        $history->jawaban = $ujian->jawaban;
        $history->kunci = $ujian->kunci;
        $history->save();

        // Delete ujian record
        $ujian->delete();
        
        return redirect()->route('nonasn.simulasi.pppk.hasil-wawancara');
    }

    public function hasilWawancara()
    {
        $hasil = HasilUjianWawancara::whereId_ptt(auth()->user()->id_ptt)->latest('id')->first();

        return view('nonasn.simulasi.pppk.hasil-wawancara', compact('hasil'));
    }

    public function storeMansoskul()
    {
        $count = UjianMansoskul::where('id_ptt', auth()->user()->id_ptt)->count();
        if ($count > 0) return redirect('simulasi-pppk/ujian-mansoskul/1');

        $client = new Client([ 'verify' => false ]);

        try {
            $fetch = $client->get('https://apps2.bkd.jatimprov.go.id/cat-pppk/api/soal-mansoskul');
            $response = json_decode($fetch->getBody());

            for ($i = 0; $i < 45 ; $i++) {
                $jawaban_kosong[$i] = '0';
            }
    
            $jawaban_kosong = implode(',', $jawaban_kosong);
            
            $ujian = new UjianMansoskul();
            $ujian->id_ptt = auth()->user()->id_ptt;
            $ujian->id_soal = $response->data->soal_id;
            $ujian->soal = $response->data->deskripsi;
            $ujian->opsi1 = $response->data->opsi_a;
            $ujian->opsi2 = $response->data->opsi_b;
            $ujian->opsi3 = $response->data->opsi_c;
            $ujian->opsi4 = $response->data->opsi_d;
            $ujian->opsi5 = $response->data->opsi_e;
            $ujian->jawaban = $jawaban_kosong;
            $ujian->kunci = $response->data->kunci;
            $ujian->nilai_manajerial = 0;
            $ujian->nilai_soskul = 0;
            $ujian->nilai_total = 0;
            $ujian->save();
    
            return redirect('simulasi-pppk/ujian-mansoskul/1');
        } catch (ClientException $e) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function showMansoskul($id)
    {
        $count = UjianMansoskul::where('id_ptt', auth()->user()->id_ptt)->count();
        if ($count < 1 OR $id < 1 OR $id > 45) {
            return redirect('ujian-mansoskul/1');
        }

        $fetch_data = UjianMansoskul::where('id_ptt', auth()->user()->id_ptt)->first();
        $id_ujian = $fetch_data->id;
        $jawaban_user = explode(",", $fetch_data->jawaban);

        $soal = explode("|", $fetch_data->soal);
        $soal = $soal[$id-1];
        $opsi1 = explode("|", $fetch_data->opsi1);
        $opsi1 = $opsi1[$id-1];
        $opsi2 = explode("|", $fetch_data->opsi2);
        $opsi2 = $opsi2[$id-1];
        $opsi3 = explode("|", $fetch_data->opsi3);
        $opsi3 = $opsi3[$id-1];
        $opsi4 = explode("|", $fetch_data->opsi4);
        $opsi4 = $opsi4[$id-1];
        $opsi5 = explode("|", $fetch_data->opsi5);
        $opsi5 = $opsi5[$id-1];

        $waktu = $fetch_data->created_at->timestamp;

        // Count how much the empty answer
        for ($i = 0, $j = 0; $i < 45; $i++) { 
          if ($jawaban_user[$i] == '0') {
                $j++;
            }
        }

        return view('nonasn.simulasi.pppk.ujian-mansoskul',compact('soal','opsi1','opsi2','opsi3','opsi4','opsi5'))
                ->with('id_ujian', $id_ujian)
                ->with('nomor_sekarang', $id)
                ->with('jawaban', $jawaban_user)
                ->with('waktu', $waktu)
                ->with('jawaban_kosong', $j);
    }

    public function updateMansoskul(Request $request, $id)
    {
        $fetch_data = UjianMansoskul::where('id_ptt', auth()->user()->id_ptt)->first(['jawaban']);

        $jawaban_user = explode(',', $fetch_data->jawaban);

        // Replace value of current number "jawaban user" array with user answer on page
        if ($request->input('opsi') == '') {
            $jawaban_user[$id - 1] = 0;
        } else {
            $jawaban_user[$id - 1] = $request->input('opsi');
        }

        // Re-Convert array of "jawaban" into string separated by comma
        $jawaban_user = implode(',', $jawaban_user);

        // Update string of "jawaban" into ujians table in DB
        $update = UjianMansoskul::where('id_ptt', auth()->user()->id_ptt)->first();
        $update->jawaban = $jawaban_user;
        $update->save();

        // Get user ujian data
        $ujian = UjianMansoskul::where('id_ptt', '=', auth()->user()->id_ptt)->first();

        // Convert string separated by comma of "jawaban" column into array
        $jawaban = explode(',', $ujian->jawaban);

        // Convert string separated by comma of "kunci" column into array
        $kunci = explode(',', $ujian->kunci);

        // Initiate nilai counter
        $manajerial = 0;
        $soskul = 0;

        // Compare user "jawaban" with "kunci"
        for ($i = 0; $i < 45; $i++) {
            if ($i < 25) {
                $kunci_manajerial = $kunci[$i];
                for ($j = 0; $j < 4; $j++) {
                    if ($jawaban[$i] == $kunci_manajerial[$j]) {
                        if ($j == 0) {
                            $manajerial += 4;
                        } else if ($j == 1) {
                            $manajerial += 3;
                        } else if ($j == 2) {
                            $manajerial += 2;
                        } else if ($j == 3) {
                            $manajerial += 1;
                        }
                    }
                }
            } else if ($i > 24 && $i < 45) {
                $kunci_soskul = $kunci[$i];
                for ($j = 0; $j < 5; $j++) {
                    if ($jawaban[$i] == $kunci_soskul[$j]) {
                        if ($j == 0) {
                            $soskul += 5;
                        } else if ($j == 1) {
                            $soskul += 4;
                        } else if ($j == 2) {
                            $soskul += 3;
                        } else if ($j == 3) {
                            $soskul += 2;
                        } else if ($j == 4) {
                            $soskul += 1;
                        }
                    }
                }
            }
        }

        // Insert user nilai into Hasil table in DB
        $nilai = UjianMansoskul::where('id_ptt', auth()->user()->id_ptt)->first();
        $nilai->nilai_manajerial = $manajerial;
        $nilai->nilai_soskul = $soskul;
        $nilai->nilai_total = $manajerial + $soskul;
        $nilai->save();

        if ($id == 45) return back();

        return redirect('simulasi-pppk/ujian-mansoskul/' . ($id + 1));
    }

    public function destroyMansoskul($id)
    {
        $count = UjianMansoskul::where('id', $id)->count();        
        if ($count == 0) return redirect('simulasi-pppk/ujian-mansoskul/hasil');

        // Get user ujian data
        $ujian = UjianMansoskul::where('id', $id)->first();

        // move nilai from table ujian to table hasil
        $hasil = new HasilUjianMansoskul;
        $hasil->id_ptt = $ujian->id_ptt;
        $hasil->id_ujian = $ujian->id;
        $hasil->nilai_manajerial = $ujian->nilai_manajerial;
        $hasil->nilai_soskul = $ujian->nilai_soskul;
        $hasil->nilai_total = $ujian->nilai_total;
        $hasil->save();

        // move nilai from table ujian to table history
        $history = new HistoryPppk();
        $history->id_ptt = $ujian->id_ptt;
        $history->jenis_tes = 'Manajerial/Sosio Kultural';
        $history->nilai = $hasil->nilai_total;
        $history->soal = $ujian->soal;
        $history->opsi1 = $ujian->opsi1;
        $history->opsi2 = $ujian->opsi2;
        $history->opsi3 = $ujian->opsi3;
        $history->opsi4 = $ujian->opsi4;
        $history->opsi5 = $ujian->opsi5;
        $history->jawaban = $ujian->jawaban;
        $history->kunci = $ujian->kunci;
        $history->save();

        // Delete ujian record
        $ujian->delete();
        
        return redirect()->route('nonasn.simulasi.pppk.hasil-mansoskul');
    }

    public function hasilMansoskul()
    {
        $hasil = HasilUjianMansoskul::whereId_ptt(auth()->user()->id_ptt)->latest('id')->first();

        return view('nonasn.simulasi.pppk.hasil-mansoskul', compact('hasil'));
    }
}
