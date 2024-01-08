<?php

namespace App\Http\Controllers\NonAsn;

use GuzzleHttp\Client;
use App\Models\NonAsn\HistoryCpns;
use App\Models\NonAsn\HasilUjianCpns;
use App\Models\NonAsn\UjianCpns;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;

class NonasnSimulasiCpnsController extends Controller
{
    public function index()
    {
        $nilai = HasilUjianCpns::orderByDesc('created_at')->paginate(10);
        return view('nonasn.simulasi.cpns.index', compact('nilai'));
    }

    public function store()
    {
        $count = UjianCpns::where('id_ptt', auth()->user()->id_ptt)->count();
        if ($count > 1) return redirect('simulasi-cpns/ujian/1');

        $client = new Client([ 'verify' => false ]);

        try {
            $fetch = $client->get('https://apps.bkd.jatimprov.go.id/api/soal');
            $response = json_decode($fetch->getBody());

            if ($response->code != 200 || $response->msg != 'success') return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);

            for ($i = 0; $i < 100 ; $i++) {
                $jawaban_kosong[$i] = '0';
            }
    
            $jawaban_kosong = implode(',', $jawaban_kosong);
    
            $id = $response->data->id;
            $deskripsi = $response->data->deskripsi;
            $opsi1 = $response->data->opsi1;
            $opsi2 = $response->data->opsi2;
            $opsi3 = $response->data->opsi3;
            $opsi4 = $response->data->opsi4;
            $opsi5 = $response->data->opsi5;
            $kunci = $response->data->kunci;
    
            $ujian = new UjianCpns();
            $ujian->id_ptt = auth()->user()->id_ptt;
            $ujian->id_soal = $id;
            $ujian->soal = $deskripsi;
            $ujian->opsi1 = $opsi1;
            $ujian->opsi2 = $opsi2;
            $ujian->opsi3 = $opsi3;
            $ujian->opsi4 = $opsi4;
            $ujian->opsi5 = $opsi5;
            $ujian->kunci = $kunci;
            $ujian->jawaban = $jawaban_kosong;
            $ujian->nilai_twk = 0;
            $ujian->nilai_tiu = 0;
            $ujian->nilai_tkp = 0;
            $ujian->nilai_total = 0;
            $ujian->save();

            return redirect('simulasi-cpns/ujian/1');
        } catch (ClientException $e) {
            // return response()->json([
            //     'code' => $e->getResponse()->getStatusCode(),
            //     'msg' => 'something went wrong'
            // ]);
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function show($id)
    {
        $count = UjianCpns::where('id_ptt', auth()->user()->id_ptt)->count();
        if ($count < 1 OR $id < 1 OR $id > 100) {
            return redirect('ujian/1');
        }

        $fetch_data = UjianCpns::where('id_ptt', auth()->user()->id_ptt)->first();
        $id_ujian = $fetch_data->id;
        $jawaban_user = explode(",", $fetch_data->jawaban);

        $soal = explode("|", $fetch_data->soal);
        $soal = $soal[$id - 1];
        $opsi1 = explode("|", $fetch_data->opsi1);
        $opsi1 = $opsi1[$id - 1];
        $opsi2 = explode("|", $fetch_data->opsi2);
        $opsi2 = $opsi2[$id - 1];
        $opsi3 = explode("|", $fetch_data->opsi3);
        $opsi3 = $opsi3[$id - 1];
        $opsi4 = explode("|", $fetch_data->opsi4);
        $opsi4 = $opsi4[$id - 1];
        $opsi5 = explode("|", $fetch_data->opsi5);
        $opsi5 = $opsi5[$id - 1];

        $waktu = $fetch_data->created_at->timestamp;

        // Count how much the empty answer
        for ($i = 0, $j = 0; $i < 100; $i++) { 
            if ($jawaban_user[$i] == '0') {
                $j++;
            }
        }

        return view('nonasn.simulasi.cpns.ujian', compact('soal','opsi1','opsi2','opsi3','opsi4','opsi5'))
                ->with('id_ujian', $id_ujian)
                ->with('nomor_sekarang', $id)
                ->with('jawaban', $jawaban_user)
                ->with('waktu', $waktu)
                ->with('jawaban_kosong', $j);
    }

    public function update($id)
    {
        // Get necessary information (jawaban column) from ujians table in DB
        $fetch_data = UjianCpns::where('id_ptt', auth()->user()->id_ptt)->first(['jawaban']);

        // Convert string separated by comma of "jawaban" column into array
        $jawaban_user = explode(',', $fetch_data->jawaban);

        // Replace value of current number "jawaban user" array with user answer on page
        if (request()->input('opsi') == '') {
            $jawaban_user[$id - 1] = 0;
        } else {
            $jawaban_user[$id - 1] = request()->input('opsi');
        }

        // Re-Convert array of "jawaban" into string separated by comma
        $jawaban_user = implode(',', $jawaban_user);

        // Update string of "jawaban" into ujians table in DB
        $update = UjianCpns::where('id_ptt', auth()->user()->id_ptt)->first();
        $update->jawaban = $jawaban_user;
        $update->save();

        // Get user ujian data
        $ujian = UjianCpns::where('id_ptt', auth()->user()->id_ptt)->first();

        // Convert string separated by comma of "jawaban" column into array
        $jawaban = explode(',', $ujian->jawaban);

        // Convert string separated by comma of "kunci" column into array
        $kunci = explode(',', $ujian->kunci);

        // Initiate nilai counter
        $tiu = 0;
        $twk = 0;
        $tkp = 0;

        // Compare user "jawaban" with "kunci"
        for ($i = 0; $i < 100 ; $i++) { 
            if ($i < 35) {
                if ($jawaban[$i] == $kunci[$i]) {
                    $twk += 5;
                } 
            }
            else if ($i > 34 && $i < 65) {
                if ($jawaban[$i] == $kunci[$i]) {
                    $tiu += 5;
                }
            }
            else if ($i > 64 && $i < 100) {
                $kunci_tkp = $kunci[$i];
                for ($j = 0; $j < 5; $j++) {
                    if ($jawaban[$i] == $kunci_tkp[$j]) {  
                    if ($j == 0) {$tkp += 5;}
                    else if ($j == 1) {$tkp += 4;}
                    else if ($j == 2) {$tkp += 3;}
                    else if ($j == 3) {$tkp += 2;}
                    else if ($j == 4) {$tkp += 1;}
                    }
                }    
            }            
        }

        // Insert user nilai into Hasil table in DB
        $nilai = UjianCpns::where('id_ptt', auth()->user()->id_ptt)->first();
        $nilai->nilai_twk = $twk;
        $nilai->nilai_tiu = $tiu;
        $nilai->nilai_tkp = $tkp;
        $nilai->nilai_total = $twk + $tiu + $tkp;
        $nilai->save();

        if ($id == 100) return back();

        return redirect('simulasi-cpns/ujian/' . ($id + 1));
    }

    public function destroy($id)
    {
        $count = UjianCpns::where('id', $id)->count();
        
        if ($count == 0) return redirect('simulasi-cpns/ujian/hasil');

        // Get user ujian data
        $ujian = UjianCpns::where('id', $id)->first();

        // move nilai from table ujian to table hasil
        $hasil = new HasilUjianCpns;
        $hasil->id_ptt = $ujian->id_ptt;
        $hasil->nilai_twk = $ujian->nilai_twk;
        $hasil->nilai_tiu = $ujian->nilai_tiu;
        $hasil->nilai_tkp = $ujian->nilai_tkp;
        $hasil->nilai_total = $ujian->nilai_total;
        $hasil->save();

        // move nilai from table ujian to table history
        $history = new HistoryCpns;
        $history->id_ptt = $ujian->id_ptt;
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
        
        return view('nonasn.simulasi.cpns.hasil', compact('hasil', $hasil));
    }
}
