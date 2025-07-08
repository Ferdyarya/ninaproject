<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

// use App\Models\Masterpegawai;

use App\Models\User;
use App\Models\Masterdaerah;
use App\Models\Masteranggota;
use App\Models\Masterpegawai;
use Illuminate\Database\Seeder;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // DB::table('masterpegawais')->insert([
        //     'kode' => '1111',
        //     'nama' => 'Hendra',
        //     'no_telp' => '081999234478',
        // ]);

        User::create([
            'name' => 'Riska',
            'email' => 'riska@gmail.com',
            'password' => bcrypt('1'),
            'roles' => 'pimpinan'
        ]);
        User::create([
            'name' => 'Nina',
            'email' => 'nina@gmail.com',
            'password' => bcrypt('2'),
            'roles' => 'admin'
        ]);
        User::create([
            'name' => 'Nina',
            'email' => 'nina@gmail.com',
            'password' => bcrypt('2'),
            'roles' => 'admin'
        ]);

        Masterdaerah::create([
            'namadaerah' => 'Denpasar',
            'alamat' => 'Bali',
        ]);
        
        Masterpegawai::create([
            'nama' => 'Robi',
            'email' => 'robi@gmail.com',
            'no_telp' => '0819231231',
            'jeniskelamin' => 'Laki Laki',
            'tgl_lahir' => '04-03-2019'
        ]);

    }
}
