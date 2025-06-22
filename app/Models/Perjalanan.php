<?php

namespace App\Models;

use App\Models\Perjalanan;
use App\Models\Masterdaerah;
use App\Models\Masterpegawai;
use App\Models\Anggaran;
use App\Models\Partisipan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perjalanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nosurat','id_daerah','deskripsi','perihal','tanggal','status'
    ];

    public function masterdaerah()
    {
        return $this->hasOne(Masterdaerah::class, 'id', 'id_daerah');
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
