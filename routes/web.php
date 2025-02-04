<?php

use App\Models\Pengajuan;
use App\Models\Pendapatan;

// New
use App\Models\Perjalanan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
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
    $dateNow = new \DateTime();
    $jumlahsuratperjalanan = Perjalanan::count();
    $jumlahsuratarsip = Perjalanan::where('status', 'Arsipkan')->count();
    $jumlahsuratpengajuan = Pengajuan::count();
    $jumlahsuratpendapatan = Pendapatan::count();

    // Mengambil data pendapatan per tahun
    $income2023 = Pendapatan::whereYear('created_at', 2023)->count();
    $income2024 = Pendapatan::whereYear('created_at', 2024)->count();
    $income2025 = Pendapatan::whereYear('created_at', 2025)->count();


    return view('dashboard',compact(
        'dateNow','jumlahsuratperjalanan','jumlahsuratarsip','jumlahsuratpengajuan','jumlahsuratpendapatan','income2023','income2024','income2025'
    ));
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
    Route::get('suratarsip', [PerjalananController::class, 'suratArsip'])->name('suratarsip');

    // Route::put('/perjalanan/{id}/status', [PerjalananController::class, 'updateStatus'])->name('updateStatus');
    Route::put('/update-status/{id}', [PerjalananController::class, 'updateStatus'])->name('updateStatusperjalanan');




    // Disposisi Verifikasi




// Data Tables Report Report
Route::get('perjalananpdf', [PerjalananController::class, 'perjalananpdf'])->name('perjalananpdf');
Route::get('pendapatanpdf', [PendapatanController::class, 'pendapatanpdf'])->name('pendapatanpdf');
Route::get('pengajuanpdf', [PengajuanController::class, 'pengajuanpdf'])->name('pengajuanpdf');


// Recap Laporan Tampilan
Route::get('laporannya/laporanperjalanan', [PerjalananController::class, 'cetakbarangpertanggal'])->name('laporanperjalanan');
Route::get('laporannya/laporanarsipperjalanan', [PerjalananController::class, 'cetakarsipertanggal'])->name('laporanarsipperjalanan');
Route::get('laporannya/laporanpendapatan', [PendapatanController::class, 'cetakbarangpertanggal'])->name('laporanpendapatan');
Route::get('laporannya/laporanpengajuan', [PengajuanController::class, 'cetakbarangpertanggal'])->name('laporanpengajuan');
Route::get('laporannya/laporanarsippdf', [PerjalananController::class, 'laporanArsipPDF'])->name('laporanarsippdf');


// Filtering
Route::get('laporanperjalanan', [PerjalananController::class, 'filterdatebarang'])->name('perjalanan');
Route::get('laporanarsipperjalanan', [PerjalananController::class, 'filterdatearsip'])->name('arsipperjalanan');
Route::get('laporanpendapatan', [PendapatanController::class, 'filterdatebarang'])->name('pendapatan');
Route::get('laporanpengajuan', [PengajuanController::class, 'filterdatebarang'])->name('pengajuan');


// Filter Laporan
Route::get('laporanperjalananpdf/filter={filter}', [PerjalananController::class, 'laporanperjalananpdf'])->name('laporanperjalananpdf');
Route::get('laporanarsipperjalananpdf/filter={filter}', [PerjalananController::class, 'laporanarsipperjalananpdf'])->name('laporanarsipperjalananpdf');
Route::get('laporanpendapatanpdf/filter={filter}', [PendapatanController::class, 'laporanpendapatanpdf'])->name('laporanpendapatanpdf');
Route::get('laporanpengajuanpdf/filter={filter}', [PengajuanController::class, 'laporanpengajuanpdf'])->name('laporanpengajuanpdf');

// Pengajuan status
Route::put('/pengajuan/{id}/status', [PengajuanController::class, 'updateStatus'])->name('updateStatus');

// Route to show verified surat data
Route::get('suratverif', [PengajuanController::class, 'tampilanterverifikasi'])->name('suratverif');
// Route for searching surat
Route::get('suratverif/search', [PengajuanController::class, 'terverifikasipencariannomorsurat'])->name('suratverif.search');
// Route to generate PDF for printing
Route::get('suratverif/pdf', [PengajuanController::class, 'terverifikasipdf'])->name('laporansuratverifpdf');

});



// Login Register
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/loginuser', [LoginController::class, 'loginuser'])->name('loginuser');








