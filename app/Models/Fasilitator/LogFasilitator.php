<?php

namespace App\Models\Fasilitator;

use App\Models\Biodata;
use App\Models\Skpd;
use Illuminate\Database\Eloquent\Model;

class LogFasilitator extends Model
{
    protected $table = 'log';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $dates = ['tgl'];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'id_skpd', 'id')->select('id', 'name');
    }

    public function biodata()
    {
        return $this->belongsTo(Biodata::class, 'id_ptt', 'id_ptt')->select('id_ptt', 'nama', 'niptt');
    }
}
