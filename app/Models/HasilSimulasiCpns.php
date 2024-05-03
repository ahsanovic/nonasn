<?php

namespace App\Models;

use App\Models\Biodata;
use Illuminate\Database\Eloquent\Model;

class HasilSimulasiCpns extends Model
{
    protected $table = 'simulasi_cpns_hasil';

    public function pegawai()
    {
        return $this->belongsTo(Biodata::class, 'id_ptt', 'id_ptt');
    }
}
