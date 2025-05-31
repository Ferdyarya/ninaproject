<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyetopan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nosurat','id_daerah','tanggal','jumlahdana','keterangan'
    ];

    public function masterdaerah()
    {
        return $this->hasOne(Masterdaerah::class, 'id', 'id_daerah');
    }
}
