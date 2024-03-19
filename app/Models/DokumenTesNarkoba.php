<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenTesNarkoba extends Model
{
    protected $table = 'ptt_dok_tes_narkoba';
    protected $guarded = ['id'];

    public function setTglSuratAttribute($value)
    {
        list($tgl_surat,$bln_surat,$thn_surat) = explode("/", $value);
        $this->attributes['tgl_surat'] = $thn_surat . '-' . $bln_surat . '-' . $tgl_surat;
    }

    public function getTglSuratAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }
}
