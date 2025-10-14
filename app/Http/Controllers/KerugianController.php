<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Kerugian;
use App\Models\Masterdaerah;
use Illuminate\Http\Request;

class KerugianController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('search')){
            $kerugian = Kerugian::where('nosurat', 'LIKE', '%' .$request->search.'%')->paginate(10);
        }else{
            $kerugian = Kerugian::paginate(10);
        }
        return view('kerugian.index',[
            'kerugian' => $kerugian
        ]);
    }


    public function create()
    {
        $masterdaerah = Masterdaerah::all();
        return view('kerugian.create', [
            'masterdaerah' => $masterdaerah,
        ]);
        return view('kerugian.create')->with('success', 'Data Telah ditambahkan');
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_daerah' => 'required|string',
            'keterangan' => 'required|string',
            'penanggungjawab' => 'required|string',
            'jumlahkerugian' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        $nosurat = $this->generatenosurat();

        $data = $request->all(['id_daerah', 'keterangan', 'jumlahkerugian', 'tanggal','penanggungjawab']);
        $data['nosurat'] = $nosurat;

        Kerugian::create($data);

        return redirect()->route('kerugian.index')->with('success', 'Data telah ditambahkan');
    }



public function generatenosurat()
{
    // Mendapatkan surat terakhir berdasarkan tanggal
    $latestSurat = Kerugian::orderBy('created_at', 'desc')->first();

    // Menangani kasus jika belum ada surat yang tersimpan
    if (!$latestSurat) {
        return 'SRT-KRG-001';
    }

    // Mendapatkan nomor surat terakhir dan increment
    $lastKode = $latestSurat->nosurat;
    $lastNumber = (int)substr($lastKode, -3);
    $newNumber = $lastNumber + 1;

    // Generate kode surat baru
    $newKode = 'SRT-KRG-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    return $newKode;
}



    public function show($id)
    {

    }


    public function edit(Kerugian $kerugian)
    {
        $masterdaerah = Masterdaerah::all();

        return view('kerugian.edit', [
            'item' => $kerugian,
            'masterdaerah' => $masterdaerah,
        ]);
    }


    public function update(Request $request, Kerugian $kerugian)
    {
        $data = $request->all();

        $kerugian->update($data);

        //dd($data);

        return redirect()->route('kerugian.index')->with('success', 'Data Telah diupdate');

    }


    public function destroy(Kerugian $kerugian)
    {
        $kerugian->delete();
        return redirect()->route('kerugian.index')->with('success', 'Data Telah dihapus');
    }


     // Laporan Buku Surat Pusat Filter
     public function cetakkerugianpertanggal()
     {
         $kerugian = Kerugian::Paginate(10);

         return view('laporannya.laporankerugian', ['laporankerugian' => $kerugian]);
     }

     public function filterdatekerugian(Request $request)
     {
         $startDate = $request->input('dari');
         $endDate = $request->input('sampai');

          if ($startDate == '' && $endDate == '') {
             $laporankerugian = Kerugian::paginate(10);
         } else {
             $laporankerugian = Kerugian::whereDate('tanggal','>=',$startDate)
                                         ->whereDate('tanggal','<=',$endDate)
                                         ->paginate(10);
         }
         session(['filter_start_date' => $startDate]);
         session(['filter_end_date' => $endDate]);

         return view('laporannya.laporankerugian', compact('laporankerugian'));
     }


     public function laporankerugianpdf(Request $request)
{
    $startDate = session('filter_start_date');
    $endDate = session('filter_end_date');

    // Mengambil data laporan berdasarkan filter tanggal
    if ($startDate == '' && $endDate == '') {
        $laporankerugian = Kerugian::all();
    } else {
        $laporankerugian = Kerugian::whereDate('tanggal', '>=', $startDate)
                                        ->whereDate('tanggal', '<=', $endDate)
                                        ->get();
    }

    // Mengambil data pengguna yang sedang login
    $user = auth()->user();

    // Menghitung Grand Total
    $grandTotal = $laporankerugian->sum('jumlahkerugian');

    // Render view dengan menyertakan data laporan, grand total, dan informasi pengguna
    $pdf = PDF::loadview('laporannya.laporankerugianpdf', compact('laporankerugian', 'user', 'grandTotal'));

    // Mengunduh file PDF
    return $pdf->download('laporan_laporankerugian.pdf');
}
}
