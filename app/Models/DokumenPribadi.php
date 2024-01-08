<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DokumenPribadi extends Model
{
    protected $table = 'ptt_dok_pribadi';
    protected $guarded = ['id_ptt'];
    protected $primaryKey = 'id_ptt';
    public $timestamps = false;

    protected function getUpdatedAtFileKtpAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    protected function getUpdatedAtFileBpjsAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    protected function getUpdatedAtFileBpjsNakerAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
