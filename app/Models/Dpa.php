<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dpa extends Model
{
    protected $table = 'non_ptt_dpa';
    protected $guarded = ['id'];

    protected $casts = [
        'data_dpa' => 'array'
    ];
}
