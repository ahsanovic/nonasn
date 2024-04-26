<?php

namespace App\Models\Fasilitator;

use App\Models\Skpd;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserFasilitator extends Authenticatable
{
    protected $table = 'users';
    protected $fillable = ['username', 'password', 'nama_lengkap', 'email', 'no_telp', 'id_skpd', 'level', 'blokir'];
    protected $primaryKey = 'username';
    // protected $guard = 'fasilitator';
    
    public $timestamps = false;
    public $incrementing = false;

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'id_skpd', 'id');
    }
}
