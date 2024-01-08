<?php

namespace App\Models;

use App\Models\Fasilitator\Biodata;
use Illuminate\Database\Eloquent\Model;

class RefAgama extends Model
{
    protected $table = 'setting_agama';
    protected $primaryKey = 'id_agama';

    public function biodata()
    {
        return $this->hasMany(Biodata::class, 'id_agama')->whereAktif('Y');
    }
}
