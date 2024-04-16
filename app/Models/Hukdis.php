<?php

namespace App\Models;

use App\Models\RefHukdis;
use Illuminate\Database\Eloquent\Model;

class Hukdis extends Model
{
    protected $table = 'rwyt_hukdis';
    protected $guarded = ['id'];

    // mutators
    public function setTglSkAttribute($value)
    {
        list($tgl_sk,$bln_sk,$thn_sk) = explode("/", $value);
        $this->attributes['tgl_sk'] = $thn_sk . '-' . $bln_sk . '-' . $tgl_sk;
    }

    public function setTmtAwalAttribute($value)
    {
        list($tgl_surat,$bln_surat,$thn_surat) = explode("/", $value);
        $this->attributes['tmt_awal'] = $thn_surat . '-' . $bln_surat . '-' . $tgl_surat;
    }

    // accessor
    protected function getTglSkAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    protected function getTmtAwalAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function jenisHukdis()
    {
        return $this->belongsTo(RefHukdis::class, 'jenis_hukdis_id', 'id');
    }
}
