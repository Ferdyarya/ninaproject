<?php

namespace App\Models;

use App\Models\Anggaran;
use App\Models\Partisipan;
use App\Models\Perjalanan;
use App\Models\Masterdaerah;
use App\Models\Masterpangkat;
use App\Models\Masterpegawai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perjalanan extends Model
{
    use HasFactory;
    protected $fillable = ['nosurat', 'id_daerah', 'deskripsi', 'perihal', 'tanggal', 'status', 'id_pangkat'];

    public function masterdaerah()
    {
        return $this->belongsTo(Masterdaerah::class, 'id_daerah' ,'id' );
    }
    public function masterpegawai()
    {
        return $this->belongsTo(Masterpegawai::class, 'id_masterpegawai', 'id');
    }

    // public function masterpegawai()
    // {
    //     return $this->hasOne(Masterpegawai::class, 'id', 'id_masterpegawai');
    // }

    public function partisipan()
    {
        return $this->hasMany(Partisipan::class, 'id_perjalanan', 'id');
    }

    public function anggaran()
    {
        return $this->hasMany(Anggaran::class, 'id_perjalanan', 'id');
    }
}
