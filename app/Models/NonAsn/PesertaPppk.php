<?php

namespace App\Models\NonAsn;

use App\Models\NonAsn\UjianTeknis;
use Illuminate\Database\Eloquent\Model;

class PesertaPppk extends Model
{
    protected $table = 'simulasi_p3k_peserta';
    protected $guarded = ['id'];
    public $timestamps = false;
}
