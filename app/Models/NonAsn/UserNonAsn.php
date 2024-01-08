<?php

namespace App\Models\NonAsn;

use App\Models\Skpd;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserNonAsn extends Authenticatable
{
    protected $table = 'ptt_biodata';
    protected $guarded = ['id_ptt'];
    protected $primaryKey = 'id_ptt';
    
    public $timestamps = false;

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'id_skpd', 'id');
    }

    public function getRouteKeyName()
    {
        return 'niptt';   
    }
}
