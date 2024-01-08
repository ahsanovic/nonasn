<?php

namespace App\Models;

use App\Models\Biodata;
use Illuminate\Database\Eloquent\Model;

class Skpd extends Model
{
    protected $table = 'skpd';
    protected $fillable = ['id', 'pId', 'name'];
    public $timestamps = false;

    public function biodata()
    {
        return $this->hasMany(Biodata::class, 'id_skpd', 'id')->whereAktif('Y');
    }

    protected function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function eselon2($id_skpd)
    {
        $split_unor = substr($id_skpd,0,3);
        $unor_es2 = Skpd::where('id', $split_unor)->first(['name']);
        return $unor_es2;
    }

    public function eselon3($id_skpd)
    {
        $split_unor = substr($id_skpd,0,5);
        $unor_es3 = Skpd::where('id', $split_unor)->first(['name']);
        return $unor_es3;
    }

    public function eselon4($id_skpd)
    {
        $split_unor = substr($id_skpd,0,7);
        $unor_es4 = Skpd::where('id', $split_unor)->first(['name']);
        return $unor_es4;
    }

    public function bagian($id_skpd)
    {
        $split_unor = substr($id_skpd,0,9);
        $unor_bagian = Skpd::where('id', $split_unor)->first(['name']);
        return $unor_bagian;
    }

    public function subbagian($id_skpd)
    {
        $split_unor = substr($id_skpd,0,11);
        $unor_subbagian = Skpd::where('id', $split_unor)->first(['name']);
        return $unor_subbagian;
    }
}
