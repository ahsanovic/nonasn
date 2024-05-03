<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSimulasiPppkWawancara extends Model
{
    protected $table = 'simulasi_p3k_hasil_wawancara';

    public function pegawai()
    {
        return $this->belongsTo(Biodata::class, 'id_ptt', 'id_ptt');
    }
}
