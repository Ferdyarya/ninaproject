<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_perjalanan','keterangan','anggaran'
    ];

    public function perjalanan()
    {
        return $this->belongsTo(Perjalanan::class, 'id_perjalanan', 'id');
    }
}
