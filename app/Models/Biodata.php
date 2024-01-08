<?php

namespace App\Models;

use App\Models\Skpd;
use App\Models\Jabatan;
use App\Models\Pendidikan;
use App\Models\RefAgama;
use App\Models\RefJenisPtt;
use App\Models\RefKawin;
use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    protected $table = 'ptt_biodata';
    protected $guarded = ['id_ptt'];
    // protected $dates = ['thn_lahir'];
    protected $primaryKey = 'id_ptt';
    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'idSkpd';
    }

    protected function setNamaAttribute($value)
    {
        $this->attributes['nama'] = strtoupper($value);
    }
    
    protected function setThnLahirAttribute($value)
    {
        list($tgl,$bln,$thn) = explode("/", $value);
        $this->attributes['thn_lahir'] = $thn . '-' . $bln . '-' . $tgl;
    }

    protected function getThnLahirAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function jenisPtt()
    {
        return $this->belongsTo(RefJenisPtt::class, 'jenis_ptt_id');
    }

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'id_skpd', 'id');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'id_ptt', 'id_ptt')->where('aktif', 'Y');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_ptt', 'id_ptt')->where('aktif', 'Y');
    }

    public function agama()
    {
        return $this->belongsTo(RefAgama::class, 'id_agama');
    }

    public function kawin()
    {
        return $this->belongsTo(RefKawin::class, 'id_kawin');
    }
}
