<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alokasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'nosurat','id_daerah','nominal','tanggal','status','keperluan','penanggungjawab'
    ];

    public function masterdaerah()
    {
        return $this->hasOne(Masterdaerah::class, 'id', 'id_daerah');
    }
}
