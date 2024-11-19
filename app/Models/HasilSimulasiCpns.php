<?php

namespace App\Models;

use App\Models\Biodata;
use Illuminate\Database\Eloquent\Model;

class HasilSimulasiCpns extends Model
{
    protected $table = 'simulasi_cpns_hasil';

    public function biodata()
    {
        return $this->belongsTo(Biodata::class, 'id_ptt', 'id_ptt');
    }

    // Relasi ke skpd melalui Biodata
    public function skpd()
    {
        return $this->hasOneThrough(
            Skpd::class,  // Model tujuan
            Biodata::class, // Model perantara
            'id_ptt',      // Foreign key di Biodata
            'id_skpd',     // Foreign key di SKPD
            'id_ptt',      // Local key di HasilSimulasiCpns
            'id_skpd'      // Local key di Biodata
        );
    }
}
