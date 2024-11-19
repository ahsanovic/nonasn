<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSimulasiPppkManajerial extends Model
{
    protected $table = 'simulasi_p3k_hasil_mansoskul';

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
