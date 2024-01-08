<?php

namespace App\Models\NonAsn;

use App\Models\Biodata;
use App\Models\Skpd;
use Illuminate\Database\Eloquent\Model;

class LogNonAsn extends Model
{
    protected $table = 'log_ptt';
    protected $primaryKey = 'log_id';
    protected $guarded = ['log_id'];
    public $timestamps = false;
    protected $dates = ['tgl'];

    public function biodata()
    {
        return $this->belongsTo(Biodata::class, 'ptt_id', 'id_ptt')->select('id_ptt', 'niptt', 'nama', 'id_skpd');
    }
}
