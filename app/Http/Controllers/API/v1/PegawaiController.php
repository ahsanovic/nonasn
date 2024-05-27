<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Biodata;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Skpd;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    private function _urlImage()
    {
        return 'https://bkd.jatimprov.go.id/nonasn/fasilitator/image/';
    }

    private function _getSkpd($id_skpd)
    {
        $data = Skpd::whereId($id_skpd)->first('id', 'name');
        switch (strlen($id_skpd)) {
            case '3':
                $skpd = $data->eselon2($id_skpd)->name;
                break;
            case '5':
                $skpd = $data->eselon2($id_skpd)->name . ' - ' . $data->eselon3($id_skpd)->name;
                break;
            case '7':
                $skpd = $data->eselon2($id_skpd)->name . ' - ' . $data->eselon3($id_skpd)->name . ' - ' . $data->eselon4($id_skpd)->name;
                break;
            case '9':
                $skpd = $data->eselon2($id_skpd)->name . ' - ' . $data->eselon3($id_skpd)->name . ' - ' . $data->eselon4($id_skpd)->name . ' - ' . $data->bagian($id_skpd)->name;
                break;
            case '11':
                $skpd = $data->eselon2($id_skpd)->name . ' - ' . $data->eselon3($id_skpd)->name . ' - ' . $data->eselon4($id_skpd)->name . ' - ' . $data->bagian($id_skpd)->name . ' - ' . $data->subbagian($id_skpd)->name;
                break;
        }

        return $skpd;
    }

    private function _getAlamat($id_ptt)
    {
        $pegawai = Biodata::whereId_ptt($id_ptt)->first('alamat', 'kode_pos');
        [$alamat, $rt, $rw, $desa, $kec, $kab, $prov] = explode("|", $pegawai->alamat);
        
        if ($pegawai->alamat == '||||||' || $pegawai->alamat == null) return null;
        
        return [
            'alamat' => $alamat,
            'rt' => $rt,
            'rw' => $rw,
            'desa' => $desa,
            'kec' => $kec,
            'kab' => $kab,
            'prov' => $prov,
            'kode_pos' => $pegawai->kode_pos,
        ];
    }

    public function pegawaiAll(Request $request)
    {
        $biodata = Biodata::select(
                        'id_ptt',
                        'nik',
                        'id_skpd',
                        'jenis_ptt_id',
                        'nama',
                        'niptt',
                        'tempat_lahir',
                        'thn_lahir',
                        'jk',
                        'foto',
                        'id_agama',
                        'id_kawin',
                        'no_hp',
                        'no_bpjs',
                        'kelas_id',
                        'no_bpjs_naker'
                    )
                    ->whereAktif('Y')
                    ->with(['skpd', 'jenisPtt', 'agama', 'kawin'])
                    ->paginate(10);
        
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "code" => 400,
                "message" => "page must be numeric or not less than 1"
            ], 400);
        }
        
        if ($request->query('page') > $biodata->lastPage()) {
            return response()->json([
                "status" => "error",
                "code" => 404,
                "message" => "page not found"
            ], 404);
        }

        $data = [];
        foreach ($biodata as $item) {
            $data[] = [
                'jenis_pegawai' => $item->jenisPtt->jenis_ptt,
                'niptt' => $item->niptt,
                'nama' => $item->nama,
                'nik' => $item->nik,
                'tempat_lahir' => $item->tempat_lahir,
                'tgl_lahir' => $item->thn_lahir,
                'usia' => $item->getAge(),
                'jk' => $item->jk,
                'agama' => $item->agama->agama ?? null,
                'status_perkawinan' => $item->kawin->status_kawin ?? null,
                'alamat' => $this->_getAlamat($item->id_ptt),
                'no_hp' => $item->no_hp,
                'no_bpjs' => $item->no_bpjs,
                'kelas_bpjs' => $item->kelas_id,
                'no_bpjs_naker' => $item->no_bpjs_naker,
                'foto' => $this->_urlImage() . $item->foto,
                'unit_kerja' => $item->skpd->name,
                'skpd' => $this->_getSkpd($item->id_skpd),
            ];
        }

        return response()->json([
            "status" => "success",
            "code" => "200",
            "data" => $data,
            'pagination' => [
                'current_page' => $biodata->currentPage(),
                'last_page' => $biodata->lastPage(),
                'per_page' => $biodata->perPage(),
                'total' => $biodata->total(),
                'links' => [
                    'first' => $biodata->url(1),
                    'last' => $biodata->url($biodata->lastPage()),
                    'next' => $biodata->nextPageUrl(),
                    'prev' => $biodata->previousPageUrl(),
                ],
            ],
        ], 200);
    }

    public function pegawaiByNip($niptt)
    {
        $biodata = Biodata::select(
                        'id_ptt',
                        'nik',
                        'id_skpd',
                        'jenis_ptt_id',
                        'nama',
                        'niptt',
                        'tempat_lahir',
                        'thn_lahir',
                        'jk',
                        'foto',
                        'id_agama',
                        'id_kawin',
                        'no_hp',
                        'no_bpjs',
                        'kelas_id',
                        'no_bpjs_naker'
                    )
                    ->whereNiptt($niptt)
                    ->whereAktif('Y')
                    ->first();

        if (!$biodata) {
            return response()->json([
                "status" => "error",
                "code" => 404,
                "message" => "nip not found"
            ], 404);
        }

        $data = [
            'jenis_pegawai' => $biodata->jenisPtt->jenis_ptt,
            'niptt' => $biodata->niptt,
            'nama' => $biodata->nama,
            'nik' => $biodata->nik,
            'tempat_lahir' => $biodata->tempat_lahir,
            'tgl_lahir' => $biodata->thn_lahir,
            'usia' => $biodata->getAge(),
            'jk' => $biodata->jk,
            'agama' => $biodata->agama->agama ?? null,
            'status_perkawinan' => $biodata->kawin->status_kawin ?? null,
            'alamat' => $this->_getAlamat($biodata->id_ptt),
            'no_hp' => $biodata->no_hp,
            'no_bpjs' => $biodata->no_bpjs,
            'kelas_bpjs' => $biodata->kelas_id,
            'no_bpjs_naker' => $biodata->no_bpjs_naker,
            'foto' => $this->_urlImage() . $biodata->foto,
            'unit_kerja' => $biodata->skpd->name,
            'skpd' => $this->_getSkpd($biodata->id_skpd),
        ];

        return response()->json([
            "status" => "success",
            "code" => "200",
            "data" => $data
        ], 200);
    }
}
