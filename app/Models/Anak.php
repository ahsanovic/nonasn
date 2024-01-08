<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anak extends Model
{
    protected $table = 'rwyt_anak';
    protected $fillable = [
        'id_ptt',
        'suami_istri_id',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'status_anak_id',
        'pekerjaan_anak_id',
        'no_bpjs',
        'kelas_id',
        'file_bpjs'
    ];
    protected $primaryKey = 'anak_id';
    protected $dates = ['tgl_lahir'];
    public $timestamps = false;

    public function statusAnak()
    {
        return $this->belongsTo(RefStatusAnak::class, 'status_anak_id', 'status_anak_id');
    }

    public function pekerjaanAnak()
    {
        return $this->belongsTo(RefPekerjaanAnak::class, 'pekerjaan_anak_id', 'pekerjaan_id');
    }

    public function orangTua()
    {
        return $this->belongsTo(SuamiIstri::class, 'suami_istri_id', 'suami_istri_id');
    }

    public function refKelasBpjs()
    {
        return $this->belongsTo(RefKelasBpjs::class, 'id', 'kelas_id');
    }

    protected function setNamaAttribute($value)
    {
        $this->attributes['nama'] = strtoupper($value);
    }
}
