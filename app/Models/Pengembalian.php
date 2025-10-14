<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;
    protected $fillable = [
        'nosurat','id_daerah','tanggal','id_kerugian','keterangan','penanggungjawab'
    ];

    public function masterdaerah()
    {
        return $this->hasOne(Masterdaerah::class, 'id', 'id_daerah');
    }
    public function kerugian()
    {
        return $this->hasOne(Kerugian::class, 'id', 'id_kerugian');
    }
}
