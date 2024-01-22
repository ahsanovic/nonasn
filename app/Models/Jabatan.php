<?php

namespace App\Models;

use App\Models\RefJabatan;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'ptt_jabatan';
    protected $primaryKey = 'id_ptt_jab';
    protected $fillable = ['id_ptt','id_jabatan', 'id_guru_mapel', 'no_surat','tgl_surat','pejabat_penetap','tgl_mulai','tgl_akhir','gaji','ket','file','file_honor','aktif'];
    public $timestamps = false;

    public function refJabatan()
    {
        return $this->belongsTo(RefJabatan::class, 'id_jabatan', 'id_jabatan');
    }

    // mutators
    public function setTglSuratAttribute($value)
    {
        list($tgl_surat,$bln_surat,$thn_surat) = explode("/", $value);
        $this->attributes['tgl_surat'] = $thn_surat . '-' . $bln_surat . '-' . $tgl_surat;
    }

    public function setTglMulaiAttribute($value)
    {
        list($tgl_mulai,$bln_mulai,$thn_mulai) = explode("/", $value);
        $this->attributes['tgl_mulai'] = $thn_mulai . '-' . $bln_mulai . '-' . $tgl_mulai;
    }

    public function setTglAkhirAttribute($value)
    {
        list($tgl_akhir,$bln_akhir,$thn_akhir) = explode("/", $value);
        $this->attributes['tgl_akhir'] = $thn_akhir . '-' . $bln_akhir . '-' . $tgl_akhir;
    }

    // accessor
    protected function getTglMulaiAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    protected function getTglAkhirAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    protected function getTglSuratAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }
}
