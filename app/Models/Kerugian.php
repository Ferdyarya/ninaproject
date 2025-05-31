<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kerugian extends Model
{
    use HasFactory;
    protected $fillable = [
        'nosurat','id_daerah','tanggal','jumlahkerugian','keterangan'
    ];

    public function masterdaerah()
    {
        return $this->hasOne(Masterdaerah::class, 'id', 'id_daerah');
    }
}
