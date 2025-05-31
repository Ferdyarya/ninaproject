<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Pendapatan;
use App\Models\Masterdaerah;
use Illuminate\Http\Request;

class PendapatanController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('search')){
            $pendapatan = Pendapatan::where('nosurat', 'LIKE', '%' .$request->search.'%')->paginate(10);
        }else{
            $pendapatan = Pendapatan::paginate(10);
        }
        return view('pendapatan.index',[
            'pendapatan' => $pendapatan
        ]);
    }


    public function create()
    {
        $masterdaerah = Masterdaerah::all();
        return view('pendapatan.create', [
            'masterdaerah' => $masterdaerah,
        ]);
        return view('pendapatan.create')->with('success', 'Data Telah ditambahkan');
    }


    public function store(Request $request)
{
    $data = $request->all();

    // Validasi permintaan
    $request->validate([
        'id_daerah' => 'required|string',
        'tanggal' => 'required|date',
        'nominal' => 'required|numeric',
        'ketdana' => 'required|string',
        'filelaporan' => 'file|mimes:pdf',
    ]);

    // Generate kode surat
    $nosurat = $this->generatenosurat();

    // Persiapkan data untuk disimpan
    $data = $request->only(['id_daerah', 'tanggal', 'nominal', 'filelaporan','ketdana']);
    $data['nosurat'] = $nosurat;

    // Menangani file surat jika ada
    if ($request->hasFile('filelaporan')) {
        $fileName = $request->file('filelaporan')->getClientOriginalName();
        $request->file('filelaporan')->move(public_path('filelaporan'), $fileName);
        $data['filelaporan'] = $fileName;
    }

    // Debugging: Periksa nilai $data sebelum menyimpan ke database
    // dd($data);

    // Buat entri baru dengan kode surat otomatis
    Pendapatan::create($data);

    // Redirect dengan pesan sukses
    return redirect()->route('pendapatan.index')->with('success', 'Data telah ditambahkan');
}



public function generatenosurat()
{
    // Mendapatkan surat terakhir berdasarkan tanggal
    $latestSurat = Pendapatan::orderBy('created_at', 'desc')->first();

    // Menangani kasus jika belum ada surat yang tersimpan
    if (!$latestSurat) {
        return 'SRT-PDPD-001';
    }

    // Mendapatkan nomor surat terakhir dan increment
    $lastKode = $latestSurat->nosurat;
    $lastNumber = (int)substr($lastKode, -3);
    $newNumber = $lastNumber + 1;

    // Generate kode surat baru
    $newKode = 'SRT-PDPD-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    return $newKode;
}



    public function show($id)
    {

    }


    public function edit(Pendapatan $pendapatan)
    {
        $masterdaerah = Masterdaerah::all();

        return view('pendapatan.edit', [
            'item' => $pendapatan,
            'masterdaerah' => $masterdaerah,
        ]);
    }


    public function update(Request $request, Pendapatan $pendapatan)
    {
        $data = $request->all();

        $pendapatan->update($data);

        //dd($data);

        return redirect()->route('pendapatan.index')->with('success', 'Data Telah diupdate');

    }


    public function destroy(Pendapatan $pendapatan)
    {
        $pendapatan->delete();
        return redirect()->route('pendapatan.index')->with('success', 'Data Telah dihapus');
    }



     // Laporan Buku Surat Pusat Filter
     public function cetakpendapatanpertanggal()
     {
         $pendapatan = Pendapatan::Paginate(10);

         return view('laporannya.laporanpendapatan', ['laporanpendapatan' => $pendapatan]);
     }

     public function filterdatependapatan(Request $request)
     {
         $startDate = $request->input('dari');
         $endDate = $request->input('sampai');

          if ($startDate == '' && $endDate == '') {
             $laporanpendapatan = Pendapatan::paginate(10);
         } else {
             $laporanpendapatan = Pendapatan::whereDate('tanggal','>=',$startDate)
                                         ->whereDate('tanggal','<=',$endDate)
                                         ->paginate(10);
         }
         session(['filter_start_date' => $startDate]);
         session(['filter_end_date' => $endDate]);

         return view('laporannya.laporanpendapatan', compact('laporanpendapatan'));
     }


     public function laporanpendapatanpdf(Request $request)
{
    $startDate = session('filter_start_date');
    $endDate = session('filter_end_date');

    // Mengambil data laporan berdasarkan filter tanggal
    if ($startDate == '' && $endDate == '') {
        $laporanpendapatan = Pendapatan::all();
    } else {
        $laporanpendapatan = Pendapatan::whereDate('tanggal', '>=', $startDate)
                                        ->whereDate('tanggal', '<=', $endDate)
                                        ->get();
    }

    // Mengambil data pengguna yang sedang login
    $user = auth()->user();

    // Menghitung Grand Total
    $grandTotal = $laporanpendapatan->sum('nominal');

    // Render view dengan menyertakan data laporan, grand total, dan informasi pengguna
    $pdf = PDF::loadview('laporannya.laporanpendapatanpdf', compact('laporanpendapatan', 'user', 'grandTotal'));

    // Mengunduh file PDF
    return $pdf->download('laporan_laporanpendapatan.pdf');
}

}
