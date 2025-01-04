<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masterdaerah extends Model
{
    use HasFactory;
    protected $fillable = [
        'namadaerah','alamat'
   ];
}
