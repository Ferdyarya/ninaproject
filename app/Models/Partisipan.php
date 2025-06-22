<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partisipan extends Model
{
    use HasFactory;
    protected $fillable = ['id_masterpegawai', 'id_perjalanan'];

    public function perjalanan()
    {
        return $this->belongsTo(Perjalanan::class, 'id_perjalanan', 'id');
    }
    public function masterpegawai()
    {
        return $this->hasOne(Masterpegawai::class, 'id', 'id_masterpegawai');
    }
}
