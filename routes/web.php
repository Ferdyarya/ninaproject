<?php

use App\Models\Pengajuan;
use App\Models\Pendapatan;

// New
use App\Models\Perjalanan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AlokasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\PenyetopanController;
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
    $dateNow = new \DateTime();
    $jumlahsuratperjalanan = Perjalanan::count();
    $jumlahsuratarsip = Perjalanan::where('status', 'Arsipkan')->count();
    $jumlahsuratpengajuan = Pengajuan::count();
    $jumlahsuratpendapatan = Pendapatan::count();

    $monthlyIncome = [];
    for ($month = 1; $month <= 12; $month++) {
        $monthlyIncome[] = Pendapatan::whereYear('created_at', 2024)->whereMonth('created_at', $month)->count();
    }
    return view('dashboard', compact('dateNow', 'jumlahsuratperjalanan', 'jumlahsuratarsip', 'jumlahsuratpengajuan', 'jumlahsuratpendapatan', 'monthlyIncome'));
})->middleware('auth');

Route::prefix('dashboard')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Master Data
        Route::resource('masterdaerah', MasterdaerahController::class);
        Route::resource('masterpegawai', MasterpegawaiController::class);

        // Data Tables Surat
        Route::resource('perjalanan', PerjalananController::class);
        Route::resource('pendapatan', PendapatanController::class);
        Route::resource('pengajuan', PengajuanController::class);
        Route::resource('alokasi', AlokasiController::class);
        Route::resource('penyetopan', PenyetopanController::class);
        Route::get('suratarsip', [PerjalananController::class, 'suratArsip'])->name('suratarsip');

        // Route::put('/perjalanan/{id}/status', [PerjalananController::class, 'updateStatus'])->name('updateStatus');


        // Data Tables Report Report

        // Perjalana
        // Route::get('perjalananpdf', [PerjalananController::class, 'perjalananpdf'])->name('perjalananpdf');
        Route::get('laporannya/laporanperjalanan', [PerjalananController::class, 'cetakperjalananpertanggal'])->name('laporanperjalanan');
        Route::get('laporanperjalanan', [PerjalananController::class, 'filterdateperjalanan'])->name('laporanperjalanan');
        Route::get('laporanperjalananpdf/filter={filter}', [PerjalananController::class, 'laporanperjalananpdf'])->name('laporanperjalananpdf');

        // Arsip
        // Route::get('laporanarsipperjalananpdf/filter={filter}', [PerjalananController::class, 'laporanarsipperjalananpdf'])->name('laporanarsipperjalananpdf');
        Route::get('laporannya/laporanarsipperjalanan', [PerjalananController::class, 'cetakarsipertanggal'])->name('laporanarsipperjalanan');
        Route::get('laporanarsipperjalanan', [PerjalananController::class, 'filterdatearsip'])->name('arsipperjalanan');
        Route::get('laporanarsipperjalananpdf/filter={filter}', [PerjalananController::class, 'laporanarsipperjalananpdf'])->name('laporanarsippdf');

        // Pendapatan
        // Route::get('pendapatanpdf', [PendapatanController::class, 'pendapatanpdf'])->name('pendapatanpdf');
        Route::get('laporannya/laporanpendapatan', [PendapatanController::class, 'cetakpendapatanpertanggal'])->name('laporanpendapatan');
        Route::get('laporanpendapatan', [PendapatanController::class, 'filterdatependapatan'])->name('pendapatan');
        Route::get('laporanpendapatanpdf/filter={filter}', [PendapatanController::class, 'laporanpendapatanpdf'])->name('laporanpendapatanpdf');

        // Pengajuan
        // Route::get('pengajuanpdf', [PengajuanController::class, 'pengajuanpdf'])->name('pengajuanpdf');
        Route::get('laporannya/laporanpengajuan', [PengajuanController::class, 'cetakpengajuanpertanggal'])->name('laporanpengajuan');
        Route::get('laporanpengajuan', [PengajuanController::class, 'filterdatepengajuan'])->name('pengajuan');
        Route::get('laporanpengajuanpdf/filter={filter}', [PengajuanController::class, 'laporanpengajuanpdf'])->name('laporanpengajuanpdf');

        //Alokasi
        Route::get('laporannya/laporanalokasi', [AlokasiController::class, 'cetakalokasipertanggal'])->name('laporanalokasi');
        Route::get('laporanalokasi', [AlokasiController::class, 'filterdatealokasi'])->name('laporanalokasi');
        Route::get('laporanalokasipdf/filter={filter}', [AlokasiController::class, 'laporanalokasipdf'])->name('laporanalokasipdf');

        //Penyetopan
        Route::get('laporannya/laporanpenyetopan', [PenyetopanController::class, 'cetakpenyetopanpertanggal'])->name('laporanpenyetopan');
        Route::get('laporanpenyetopan', [PenyetopanController::class, 'filterdatepenyetopan'])->name('laporanpenyetopan');
        Route::get('laporanpenyetopanpdf/filter={filter}', [PenyetopanController::class, 'laporanpenyetopanpdf'])->name('laporanpenyetopanpdf');



        //status
        Route::put('/pengajuan/{id}/status', [PengajuanController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/alokasi/{id}/status', [PengajuanController::class, 'updateStatusAlokasi'])->name('updateStatusAlokasi');
        Route::put('/update-status/{id}', [PerjalananController::class, 'updateStatus'])->name('updateStatusperjalanan');

        // Route to show verified surat data
        Route::get('suratverif', [PengajuanController::class, 'tampilanterverifikasi'])->name('suratverif');
        Route::get('suratverif/search', [PengajuanController::class, 'terverifikasipencariannomorsurat'])->name('suratverif.search');
        Route::get('suratverif/pdf', [PengajuanController::class, 'terverifikasipdf'])->name('laporansuratverifpdf');
    });

// Login Register
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/loginuser', [LoginController::class, 'loginuser'])->name('loginuser');
