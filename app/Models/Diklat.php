<?php

namespace App\Models;

use App\Models\RefJenisDiklat;
use Illuminate\Database\Eloquent\Model;

class Diklat extends Model
{
    protected $table = 'ptt_diklat';
    protected $guarded = ['id'];

    public function setTglSertifikatAttribute($value)
    {
        list($tgl_sertifikat,$bln_sertifikat,$thn_sertifikat) = explode("/", $value);
        $this->attributes['tgl_sertifikat'] = $thn_sertifikat . '-' . $bln_sertifikat . '-' . $tgl_sertifikat;
    }

    public function getTglSertifikatAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setTglMulaiAttribute($value)
    {
        list($tgl_mulai,$bln_mulai,$thn_mulai) = explode("/", $value);
        $this->attributes['tgl_mulai'] = $thn_mulai . '-' . $bln_mulai . '-' . $tgl_mulai;
    }

    public function getTglMulaiAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setTglSelesaiAttribute($value)
    {
        list($tgl_selesai,$bln_selesai,$thn_selesai) = explode("/", $value);
        $this->attributes['tgl_selesai'] = $thn_selesai . '-' . $bln_selesai . '-' . $tgl_selesai;
    }

    public function getTglSelesaiAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function jenisDiklat()
    {
        return $this->belongsTo(RefJenisDiklat::class, 'jenis_diklat_id', 'id');
    }
}
