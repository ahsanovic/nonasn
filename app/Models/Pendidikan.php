<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    protected $table = 'ptt_pendidikan';
    protected $guarded = ['id_ptt_pendidikan'];
    protected $primaryKey = 'id_ptt_pendidikan';
    public $timestamps = false;

    public function setTglIjazahPtAttribute($value)
    {
        list($tgl_ijazah_pt,$bln_ijazah_pt,$thn_ijazah_pt) = explode("/", $value);
        $this->attributes['tgl_ijazah_pt'] = $thn_ijazah_pt . '-' . $bln_ijazah_pt . '-' . $tgl_ijazah_pt;
    }

    public function getTglIjazahPtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setTglIjazahSmaAttribute($value)
    {
        list($tgl_ijazah_sma,$bln_ijazah_sma,$thn_ijazah_sma) = explode("/", $value);
        $this->attributes['tgl_ijazah_sma'] = $thn_ijazah_sma . '-' . $bln_ijazah_sma . '-' . $tgl_ijazah_sma;
    }

    public function getTglIjazahSmaAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setNamaSekolahSmaAttribute($value)
    {
        $this->attributes['nama_sekolah_sma'] = strtoupper($value);
    }

    public function setJurusanSmaAttribute($value)
    {
        $this->attributes['jurusan_sma'] = strtoupper($value);
    }

    public function setNamaPtAttribute($value)
    {
        $this->attributes['nama_pt'] = strtoupper($value);
    }

    public function setFakultasPtAttribute($value)
    {
        $this->attributes['fakultas_pt'] = strtoupper($value);
    }

    public function setJurusanProdiPtAttribute($value)
    {
        $this->attributes['jurusan_prodi_pt'] = strtoupper($value);
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'id_jenjang', 'id_jenjang');
    }
}
