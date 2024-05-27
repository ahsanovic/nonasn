<?php

namespace App\Models;

use App\Models\RefPekerjaan;
use App\Models\RefSuamiIstri;
use Illuminate\Database\Eloquent\Model;

class SuamiIstri extends Model
{
    protected $table = 'rwyt_suami_istri';
    protected $primaryKey = 'suami_istri_id';
    protected $fillable = [
        'id_ptt',
        'nama_suami_istri',
        'status_suami_istri_id',
        'tempat_lahir',
        'tgl_lahir',
        'pekerjaan_id',
        'instansi',
        'aktif',
        'no_bpjs',
        'kelas_id',
        'file_bpjs'
    ];
    protected $dates = ['tgl_lahir'];
    public $timestamps = false;

    public function refSuamiIstri()
    {
        return $this->belongsTo(RefSuamiIstri::class, 'status_suami_istri_id', 'status_suami_istri_id');
    }

    public function refKelasBpjs()
    {
        return $this->belongsTo(RefKelasBpjs::class, 'id', 'kelas_id');
    }

    public function pekerjaan()
    {
        return $this->belongsTo(RefPekerjaan::class, 'pekerjaan_id', 'pekerjaan_id');
    }

    public function getRouteKeyName()
    {
        return 'suami_istri_id';
    }

    // this is mutator, to set uppercase when insert into db
    public function setNamaSuamiIstriAttribute($value)
    {
        $this->attributes['nama_suami_istri'] = strtoupper($value);
    }

    protected function getTglLahirAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }
}
