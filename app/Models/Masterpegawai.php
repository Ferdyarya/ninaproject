<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masterpegawai extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama','email','no_telp','jeniskelamin','tgl_lahir','id_pangkat'
   ];

   public function masterpangkat()
    {
        return $this->hasOne(Masterpangkat::class, 'id', 'id_pangkat');
    }
}
