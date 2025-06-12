<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Anak;
use App\Models\Skpd;
use App\Models\Jabatan;
use App\Models\RefAgama;
use App\Models\RefKawin;
use App\Models\Pendidikan;
use App\Models\SuamiIstri;
use App\Models\RefJenisPtt;
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

    public function getAge()
    {
        if ($this->attributes['thn_lahir'] != null) {
            [$thn, $bln, $tgl] = explode('-', $this->attributes['thn_lahir']);
            return Carbon::createFromDate($thn, $bln, $tgl)->diff(Carbon::now())->format('%y tahun %m bulan %d hari');
        }
    }

    // protected function setNamaAttribute($value)
    // {
    //     $this->attributes['nama'] = strtoupper($value);
    // }

    protected function setThnLahirAttribute($value)
    {
        list($tgl, $bln, $thn) = explode("/", $value);
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
        return $this->hasOne(Pendidikan::class, 'id_ptt', 'id_ptt')->where('aktif', 'Y');
    }

    public function jabatan()
    {
        return $this->hasOne(Jabatan::class, 'id_ptt', 'id_ptt')->where('aktif', 'Y');
    }

    public function agama()
    {
        return $this->belongsTo(RefAgama::class, 'id_agama');
    }

    public function kawin()
    {
        return $this->belongsTo(RefKawin::class, 'id_kawin');
    }

    public function suamiIstri()
    {
        return $this->belongsTo(SuamiIstri::class, 'id_ptt', 'id_ptt')->where('aktif', 'Y');
    }

    public function anak()
    {
        return $this->hasMany(Anak::class, 'id_ptt', 'id_ptt');
    }
}
