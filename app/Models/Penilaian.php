<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'ptt_penilaian';
    protected $primaryKey = 'id_ptt_penilaian';
    protected $fillable = ['id_ptt','tahun','nilai','rekomendasi','file'];
    public $timestamps = false;
}
