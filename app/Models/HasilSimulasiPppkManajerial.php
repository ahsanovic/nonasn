<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSimulasiPppkManajerial extends Model
{
    protected $table = 'simulasi_p3k_hasil_mansoskul';

    public function pegawai()
    {
        return $this->belongsTo(Biodata::class, 'id_ptt', 'id_ptt');
    }
}
