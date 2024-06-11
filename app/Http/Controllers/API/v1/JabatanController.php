<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Biodata;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JabatanController extends Controller
{
    public function index(Request $request, $niptt, $idJabatan = null)
    {
        if (!is_numeric($niptt)) {
            return response()->json([
                "status" => "error",
                "code" => 400,
                "message" => "nip must be number"
            ], 400);
        }

        $pegawai = Biodata::whereNiptt($niptt)
                            ->whereAktif('Y')
                            ->first(['id_ptt', 'id_skpd']);
        
        $organizationId = $request->attributes->get('organization_id');

        if (!in_array($pegawai->id_skpd, getScopeIdSkpdApi($organizationId))) {
            return response()->json([
                "status" => "error",
                "code" => 401,
                "message" => "unauthorized. employee not in the organization scope"
            ], 401);
        }

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
                'file' => !empty($jabatan->file) ? $jabatan->file : null,
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
                'file' => !empty($item->file) ? $item->file : null,
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
