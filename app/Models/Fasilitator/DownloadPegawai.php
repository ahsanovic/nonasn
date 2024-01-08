<?php

namespace App\Models\Fasilitator;

use Illuminate\Database\Eloquent\Model;

class DownloadPegawai extends Model
{
    protected $table = 'download';
    protected $primaryKey = 'id_ptt';
    protected $guarded = ['id_ptt'];
    public $timestamps = false;

    // exclude column in query
    protected $hidden = ['id_ptt'];

    protected function setNamaAttribute($value)
    {
        $this->attributes['nama'] = strtoupper($value);
    }

    protected function setTglLahirAttribute($value)
    {
        list($tgl,$bln,$thn) = explode("/", $value);
        $this->attributes['tgl_lahir'] = $thn . '-' . $bln . '-' . $tgl;
    }

    protected function setNamaSekolahAttribute($value)
    {
        if ($value != null) {
            $this->attributes['nama_sekolah'] = strtoupper($value);
        } else {
            $this->attributes['nama_sekolah'] = null;
        }
    }

    protected function setJurusanAttribute($value)
    {
        if ($value != null) {
            $this->attributes['jurusan'] = strtoupper($value);
        } else {
            $this->attributes['jurusan'] = null;
        }
    }

    public function setTglSkAttribute($value)
    {
        if ($value != null) {
            list($tgl_surat,$bln_surat,$thn_surat) = explode("/", $value);
            $this->attributes['tgl_sk'] = $thn_surat . '-' . $bln_surat . '-' . $tgl_surat;
        } else {
            $this->attributes['tgl_sk'] = null;
        }
    }

    public function setTglMulaiAttribute($value)
    {
        if ($value != null) {
            list($tgl_mulai,$bln_mulai,$thn_mulai) = explode("/", $value);
            $this->attributes['tgl_mulai'] = $thn_mulai . '-' . $bln_mulai . '-' . $tgl_mulai;
        } else {
            $this->attributes['tgl_mulai'] = null;
        }
    }

    public function setTglAkhirAttribute($value)
    {
        if ($value != null) {
            list($tgl_akhir,$bln_akhir,$thn_akhir) = explode("/", $value);
            $this->attributes['tgl_akhir'] = $thn_akhir . '-' . $bln_akhir . '-' . $tgl_akhir;
        } else {
            $this->attributes['tgl_akhir'] = null;
        }
    }
}
