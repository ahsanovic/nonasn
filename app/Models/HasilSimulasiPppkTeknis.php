<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSimulasiPppkTeknis extends Model
{
    protected $table = 'simulasi_p3k_hasil_teknis';

    public function biodata()
    {
        return $this->belongsTo(Biodata::class, 'id_ptt', 'id_ptt');
    }
}
