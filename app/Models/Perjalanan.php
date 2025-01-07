<?php

namespace App\Models;

use App\Models\Perjalanan;
use App\Models\Masterdaerah;
use App\Models\Masterpegawai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perjalanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nosurat','id_daerah','id_pegawai','deskripsi','perihal','tanggal','status'
    ];

    public function masterdaerah()
    {
        return $this->hasOne(Masterdaerah::class, 'id', 'id_daerah');
    }

    public function masterpegawai()
    {
        return $this->hasOne(Masterpegawai::class, 'id', 'id_pegawai');
    }
}
