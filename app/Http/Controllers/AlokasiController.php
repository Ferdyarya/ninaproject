<?php

namespace App\Http\Controllers;

use App\Models\Masterdaerah;
use Illuminate\Http\Request;

class AlokasiController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('search')){
            $alokasi = Alokasi::where('nosurat', 'LIKE', '%' .$request->search.'%')->paginate(10);
        }else{
            $alokasi = Alokasi::paginate(10);
        }
        return view('alokasi.index',[
            'alokasi' => $alokasi
        ]);
    }


    public function create()
    {
        $masterdaerah = Masterdaerah::all();
        return view('alokasi.create', [
            'masterdaerah' => $masterdaerah,
        ]);
        return view('alokasi.create')->with('success', 'Data Telah ditambahkan');
    }


    public function store(Request $request)
    {
        // Generate kode surat
        $nosurat = $this->generatenosurat();

        // Ambil data dari request dan tambahkan kode surat
        $data['nosurat'] = $nosurat;

        // Menyimpan data ke database
        Alokasi::create($data);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('alokasi.index')->with('success', 'Data telah ditambahkan');
    }



public function generatenosurat()
{
    // Mendapatkan surat terakhir berdasarkan tanggal
    $latestSurat = Alokasi::orderBy('created_at', 'desc')->first();

    // Menangani kasus jika belum ada surat yang tersimpan
    if (!$latestSurat) {
        return 'SRT-ALK-001';
    }

    // Mendapatkan nomor surat terakhir dan increment
    $lastKode = $latestSurat->nosurat;
    $lastNumber = (int)substr($lastKode, -3);
    $newNumber = $lastNumber + 1;

    // Generate kode surat baru
    $newKode = 'SRT-ALK-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    return $newKode;
}



    public function show($id)
    {

    }


    public function edit(Alokasi $alokasi)
    {
        $masterdaerah = Masterdaerah::all();

        return view('alokasi.edit', [
            'item' => $alokasi,
            'masterdaerah' => $masterdaerah,
        ]);
    }


    public function update(Request $request, Alokasi $alokasi)
    {
        $data = $request->all();

        $alokasi->update($data);

        //dd($data);

        return redirect()->route('alokasi.index')->with('success', 'Data Telah diupdate');

    }


    public function destroy(Alokasi $alokasi)
    {
        $alokasi->delete();
        return redirect()->route('alokasi.index')->with('success', 'Data Telah dihapus');
    }


    public function updateStatusAlokasi(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Terverifikasi,Ditolak',
        ]);

        // Find the rawatrumahkaca entry by ID
        $pengajuan = Pengajuan::findOrFail($id);

        // Update the status based on the form input
        $pengajuan->status = $validated['status'];
        $pengajuan->save();

        // Redirect back to the suratmasuk page with a success message
        return redirect()->route('alokasi.index')->with('success', 'Status surat berhasil diperbarui.');
    }


     // Laporan Buku Surat Pusat Filter
     public function cetakalokasipertanggal()
     {
         $alokasi = Alokasi::Paginate(10);

         return view('laporannya.laporanalokasi', ['laporanalokasi' => $alokasi]);
     }

     public function filterdatealokasi(Request $request)
     {
         $startDate = $request->input('dari');
         $endDate = $request->input('sampai');

          if ($startDate == '' && $endDate == '') {
             $laporanalokasi = Alokasi::paginate(10);
         } else {
             $laporanalokasi = Alokasi::whereDate('tanggal','>=',$startDate)
                                         ->whereDate('tanggal','<=',$endDate)
                                         ->paginate(10);
         }
         session(['filter_start_date' => $startDate]);
         session(['filter_end_date' => $endDate]);

         return view('laporannya.laporanalokasi', compact('laporanalokasi'));
     }


     public function laporanalokasipdf(Request $request)
{
    $startDate = session('filter_start_date');
    $endDate = session('filter_end_date');

    // Mengambil data laporan berdasarkan filter tanggal
    if ($startDate == '' && $endDate == '') {
        $laporanalokasi = Alokasi::all();
    } else {
        $laporanalokasi = Alokasi::whereDate('tanggal', '>=', $startDate)
                                        ->whereDate('tanggal', '<=', $endDate)
                                        ->get();
    }

    // Mengambil data pengguna yang sedang login
    $user = auth()->user();

    // Menghitung Grand Total
    $grandTotal = $laporanalokasi->sum('nominal');

    // Render view dengan menyertakan data laporan, grand total, dan informasi pengguna
    $pdf = PDF::loadview('laporannya.laporanalokasipdf', compact('laporanalokasi', 'user', 'grandTotal'));

    // Mengunduh file PDF
    return $pdf->download('laporan_laporanalokasi.pdf');
}
}
