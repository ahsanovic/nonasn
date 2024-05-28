<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    private function _urlFile()
    {
        return 'https://bkd.jatimprov.go.id/nonasn/fasilitator/jabatan/';
    }

    public function index($niptt, $idJabatan = null)
    {
        if (!is_numeric($niptt)) {
            return response()->json([
                "status" => "error",
                "code" => 400,
                "message" => "nip must be number"
            ], 400);
        }

        $pegawai = Biodata::whereNiptt($niptt)->first(['id_ptt']);
        if (!$pegawai) {
            return response()->json([
                "status" => "error",
                "code" => 404,
                "message" => "nip not found"
            ], 404);
        }
        
        if ($idJabatan) {
            if (!is_numeric($idJabatan)) {
                return response()->json([
                    "status" => "error",
                    "code" => 400,
                    "message" => "id must be number"
                ], 400);
            }

            $jabatan = Jabatan::with(['refJabatan'])->whereId_ptt_jab($idJabatan)->first();
            if (!$jabatan) {
                return response()->json([
                    "status" => "error",
                    "code" => 404,
                    "message" => "id not found"
                ], 404);
            }

            if ($jabatan->id_ptt != $pegawai->id_ptt) {
                return response()->json([
                    "status" => "error",
                    "code" => 404,
                    "message" => "jabatan not found"
                ], 404);
            }

            $data = [
                'nama_jabatan' => $jabatan->refJabatan->name ?? null,
                'no_sk' => $jabatan->no_surat,
                'tgl_sk' => $jabatan->tgl_surat,
                'tgl_mulai_kontrak' => $jabatan->tgl_mulai,
                'tgl_akhir_kontrak' => $jabatan->tgl_akhir,
                'pejabat_penetap' => $jabatan->pejabat_penetap,
                'file' => !empty($jabatan->file) ? $this->_urlFile() . $jabatan->file : null,
                'status' => $jabatan->aktif
            ];
            
            return response()->json([
                "status" => "success",
                "code" => 200,
                "data" => $data
            ], 200);
        }
        
        $jabatan = Jabatan::with(['refJabatan'])->whereId_ptt($pegawai->id_ptt)->get();
        $data = [];
        foreach ($jabatan as $item) {
            $data[] = [
                'id_jabatan' => $item->id_ptt_jab,
                'nama_jabatan' => $item->refJabatan->name ?? null,
                'no_sk' => $item->no_surat,
                'tgl_sk' => $item->tgl_surat,
                'tgl_mulai_kontrak' => $item->tgl_mulai,
                'tgl_akhir_kontrak' => $item->tgl_akhir,
                'pejabat_penetap' => $item->pejabat_penetap,
                'file' => !empty($item->file) ? $this->_urlFile() . $item->file : null,
                'status' => $item->aktif
            ];
        }        

        return response()->json([
            "status" => "success",
            "code" => 200,
            "data" => $data
        ], 200);
    }
}
