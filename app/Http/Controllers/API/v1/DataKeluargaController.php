<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Skpd;
use App\Models\Biodata;
use App\Http\Controllers\Controller;

class DataKeluargaController extends Controller
{
    private function _urlImagePasangan()
    {
        return 'https://bkd.jatimprov.go.id/nonasn/fasilitator/suami-istri/';
    }

    private function _urlImageAnak()
    {
        return 'https://bkd.jatimprov.go.id/nonasn/fasilitator/anak/';
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
    
    public function getAll()
    {
        $biodata = Biodata::select(
                        'id_ptt',
                        'id_skpd',
                        'jenis_ptt_id',
                        'nama',
                        'niptt',
                        'tempat_lahir',
                        'thn_lahir',
                        'jk',
                        'id_kawin',
                        'no_bpjs',
                        'kelas_id',
                        'no_bpjs_naker'
                    )
                    ->whereAktif('Y')
                    ->with([
                        'skpd',
                        'jenisPtt',
                        'kawin',
                        'suamiIstri',
                        'anak'
                    ])
                    ->paginate(10);
        
        $data = [];
        foreach ($biodata as $item) {
            $anak = [];
            foreach ($item->anak as $child) {
                $anak[] = [
                    'nama' => $child->nama,
                    'tempat_lahir' => $child->tempat_lahir,
                    'tgl_lahir' => $child->tgl_lahir,
                    'status' => $child->statusAnak->status_anak ?? null,
                    'pekerjaan' => $child->pekerjaanAnak->pekerjaan ?? null,
                    'no_bpjs' => $child->no_bpjs,
                    'kelas_bpjs' => $child->kelas_id,
                    'file_bpjs' => !empty($item->file_bpjs) ? $this->_urlImageAnak() . $item->file_bpjs : null
                ];
            }

            $data[] = [
                'jenis_pegawai' => $item->jenisPtt->jenis_ptt,
                'niptt' => $item->niptt,
                'nama' => $item->nama,
                'tempat_lahir' => $item->tempat_lahir,
                'tgl_lahir' => $item->thn_lahir,
                'jk' => $item->jk,
                'status_perkawinan' => $item->kawin->status_kawin ?? null,
                'no_bpjs' => $item->no_bpjs,
                'kelas_bpjs' => $item->kelas_id,
                'no_bpjs_naker' => $item->no_bpjs_naker,
                'unit_kerja' => $item->skpd->name,
                'skpd' => $this->_getSkpd($item->id_skpd),
                'pasangan' => [
                    'nama' => $item->suamiIstri->nama_suami_istri ?? null,
                    'tempat_lahir' => $item->suamiIstri->tempat_lahir ?? null,
                    'tgl_lahir' => $item->suamiIstri->tgl_lahir ?? null,
                    'no_bpjs' => $item->suamiIstri->no_bpjs ?? null,
                    'kelas_bpjs' => $item->suamiIstri->kelas_id ?? null,
                    'file_bpjs' => !empty($item->suamiIstri->file_bpjs) ? $this->_urlImagePasangan() . $item->suamiIstri->file_bpjs : null
                ],
                'anak' => $anak,
            ];
        }

        return response()->json([
            "status" => "success",
            "code" => "200",
            "data" => $data,
            "pagination" => [
                "current_page" => $biodata->currentPage(),
                "last_page" => $biodata->lastPage(),
                "per_page" => $biodata->perPage(),
                "total" => $biodata->total(),
                "links" => [
                    "first" => $biodata->url(1),
                    "last" => $biodata->url($biodata->lastPage()),
                    "next" => $biodata->nextPageUrl(),
                    "prev" => $biodata->previousPageUrl(),
                ],
            ],
        ], 200);
    }

    public function getByNip($niptt)
    {
        $biodata = Biodata::select(
                        'id_ptt',
                        'id_skpd',
                        'jenis_ptt_id',
                        'nama',
                        'niptt',
                        'tempat_lahir',
                        'thn_lahir',
                        'jk',
                        'id_kawin',
                        'no_bpjs',
                        'kelas_id',
                        'no_bpjs_naker'
                    )
                    ->whereNiptt($niptt)
                    ->whereAktif('Y')
                    ->with([
                        'skpd',
                        'jenisPtt',
                        'kawin',
                        'suamiIstri',
                        'anak'
                    ])
                    ->first();
        
        $anak = [];
        foreach ($biodata->anak as $child) {
            $anak[] = [
                'nama' => $child->nama,
                'tempat_lahir' => $child->tempat_lahir,
                'tgl_lahir' => $child->tgl_lahir,
                'status' => $child->statusAnak->status_anak ?? null,
                'pekerjaan' => $child->pekerjaanAnak->pekerjaan ?? null,
                'no_bpjs' => $child->no_bpjs,
                'kelas_bpjs' => $child->kelas_id,
                'file_bpjs' => !empty($biodata->file_bpjs) ? $this->_urlImageAnak() . $biodata->file_bpjs : null
            ];
        }

        $data = [
            'jenis_pegawai' => $biodata->jenisPtt->jenis_ptt,
            'niptt' => $biodata->niptt,
            'nama' => $biodata->nama,
            'tempat_lahir' => $biodata->tempat_lahir,
            'tgl_lahir' => $biodata->thn_lahir,
            'jk' => $biodata->jk,
            'status_perkawinan' => $biodata->kawin->status_kawin ?? null,
            'no_bpjs' => $biodata->no_bpjs,
            'kelas_bpjs' => $biodata->kelas_id,
            'no_bpjs_naker' => $biodata->no_bpjs_naker,
            'unit_kerja' => $biodata->skpd->name,
            'skpd' => $this->_getSkpd($biodata->id_skpd),
            'pasangan' => [
                'nama' => $biodata->suamiIstri->nama_suami_istri ?? null,
                'tempat_lahir' => $biodata->suamiIstri->tempat_lahir ?? null,
                'tgl_lahir' => $biodata->suamiIstri->tgl_lahir ?? null,
                'no_bpjs' => $biodata->suamiIstri->no_bpjs ?? null,
                'kelas_bpjs' => $biodata->suamiIstri->kelas_id ?? null,
                'file_bpjs' => !empty($biodata->suamiIstri->file_bpjs) ? $this->_urlImagePasangan() . $biodata->suamiIstri->file_bpjs : null
            ],
            'anak' => $anak,
        ];

        return response()->json([
            "status" => "success",
            "code" => "200",
            "data" => $data,
        ], 200);
    }
}
