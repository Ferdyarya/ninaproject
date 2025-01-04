<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// New
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\PerjalananController;
use App\Http\Controllers\SuratpusatController;
use App\Http\Controllers\MastercabangController;
use App\Http\Controllers\MasterdaerahController;
use App\Http\Controllers\MasterpegawaiController;
use App\Http\Controllers\SuratdisposisiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    return view('dashboard');
})->middleware('auth');


Route::prefix('dashboard')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('masterdaerah', MasterdaerahController::class);
    Route::resource('masterpegawai', MasterpegawaiController::class);

    // Data Tables Surat
    Route::resource('perjalanan', PerjalananController::class);
    Route::resource('pendapatan', PendapatanController::class);
    Route::resource('pengajuan', PengajuanController::class);

    Route::put('/perjalanan/{id}/status', [PerjalananController::class, 'updateStatus'])->name('updateStatus');



// Data Tables Report Report
Route::get('perjalananpdf', [PerjalananController::class, 'perjalananpdf'])->name('perjalananpdf');
Route::get('pendapatanpdf', [PendapatanController::class, 'pendapatanpdf'])->name('pendapatanpdf');
Route::get('pengajuanpdf', [PengajuanController::class, 'pengajuanpdf'])->name('pengajuanpdf');


// Recap Laporan Tampilan
Route::get('laporannya/laporanperjalanan', [PerjalananController::class, 'cetakbarangpertanggal'])->name('laporanperjalanan');
Route::get('laporannya/laporanpendapatan', [PendapatanController::class, 'cetakbarangpertanggal'])->name('laporanpendapatan');
Route::get('laporannya/laporanpengajuan', [PengajuanController::class, 'cetakbarangpertanggal'])->name('laporanpengajuan');


// Filtering
Route::get('laporanperjalanan', [PerjalananController::class, 'filterdatebarang'])->name('perjalanan');
Route::get('laporanpendapatan', [PendapatanController::class, 'filterdatebarang'])->name('pendapatan');
Route::get('laporanpengajuan', [PengajuanController::class, 'filterdatebarang'])->name('pengajuan');


// Filter Laporan
Route::get('laporanperjalananpdf/filter={filter}', [PerjalananController::class, 'laporanperjalananpdf'])->name('laporanperjalananpdf');
Route::get('laporanpendapatanpdf/filter={filter}', [PendapatanController::class, 'laporanpendapatanpdf'])->name('laporanpendapatanpdf');
Route::get('laporanpengajuanpdf/filter={filter}', [PengajuanController::class, 'laporanpengajuanpdf'])->name('laporanpengajuanpdf');

// Pengajuan status
Route::put('/pengajuan/{id}/status', [PengajuanController::class, 'updateStatus'])->name('updateStatus');

});



// Login Register
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/loginuser', [LoginController::class, 'loginuser'])->name('loginuser');








