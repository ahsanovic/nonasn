<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GajiNonPtt extends Model
{
    protected $table = 'non_ptt_gaji';
    protected $guarded = ['id'];

    public function setTmtAwalAttribute($value)
    {
        list($tgl_mulai,$bln_mulai,$thn_mulai) = explode("/", $value);
        $this->attributes['tmt_awal'] = $thn_mulai . '-' . $bln_mulai . '-' . $tgl_mulai;
    }

    public function setTmtAkhirAttribute($value)
    {
        list($tgl_akhir,$bln_akhir,$thn_akhir) = explode("/", $value);
        $this->attributes['tmt_akhir'] = $thn_akhir . '-' . $bln_akhir . '-' . $tgl_akhir;
    }

    protected function getTmtAwalAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    protected function getTmtAkhirAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }
}
