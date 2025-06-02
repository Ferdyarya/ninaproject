<?php

namespace App\Http\Controllers;


use PDF;
use App\Models\Pengajuan;
use App\Models\Masterdaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index(Request $request)
{
    if($request->has('search')) {
        $search = $request->search;
        $pengajuan = Pengajuan::where('nosurat', 'LIKE', '%' . $search . '%')
                        ->orWhere('keperluan', 'LIKE', '%' . $search . '%')
                        ->paginate(10);
    } else {
        $pengajuan = Pengajuan::paginate(10);
    }

    return view('pengajuan.index', [
        'pengajuan' => $pengajuan
    ]);
}



    public function create()
    {
        $masterdaerah = Masterdaerah::all();
        return view('pengajuan.create', [
            'masterdaerah' => $masterdaerah,
        ]);
        return view('pengajuan.create')->with('success', 'Data Telah ditambahkan');
    }


    public function store(Request $request)
{
    $data = $request->all();

    // Validasi permintaan
    $request->validate([
        'id_daerah' => 'required|string',
        'keperluan' => 'required|string',
        'tanggal' => 'required|date',
        'nominal' => 'required|numeric',
    ]);

    // Generate kode surat
    $nosurat = $this->generatenosurat();

    // Persiapkan data untuk disimpan
    $data = $request->only(['id_daerah', 'tanggal', 'nominal', 'keperluan']);
    $data['nosurat'] = $nosurat;
    Pengajuan::create($data);

    return redirect()->route('pengajuan.index')->with('success', 'Data telah ditambahkan');
}



public function generatenosurat()
{
    // Mendapatkan surat terakhir berdasarkan tanggal
    $latestSurat = Pengajuan::orderBy('created_at', 'desc')->first();

    // Menangani kasus jika belum ada surat yang tersimpan
    if (!$latestSurat) {
        return 'SRT-PJN-001';
    }

    // Mendapatkan nomor surat terakhir dan increment
    $lastKode = $latestSurat->nosurat;
    $lastNumber = (int)substr($lastKode, -3);
    $newNumber = $lastNumber + 1;

    // Generate kode surat baru
    $newKode = 'SRT-PJN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    return $newKode;
}



    public function show($id)
    {

    }


    public function edit(Pengajuan $pengajuan)
    {
        $masterdaerah = Masterdaerah::all();

        return view('pengajuan.edit', [
            'item' => $pengajuan,
            'masterdaerah' => $masterdaerah,
        ]);
    }


    public function update(Request $request, Pengajuan $pengajuan)
    {
        $data = $request->all();

        $pengajuan->update($data);

        //dd($data);

        return redirect()->route('pengajuan.index')->with('success', 'Data Telah diupdate');

    }


    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Data Telah dihapus');
    }



     // Laporan Buku Surat Pusat Filter
     public function cetakpengajuanpertanggal()
     {
         $pengajuan = Pengajuan::Paginate(10);

         return view('laporannya.laporanpengajuan', ['laporanpengajuan' => $pengajuan]);
     }

     public function filterdatepengajuan(Request $request)
     {
         $startDate = $request->input('dari');
         $endDate = $request->input('sampai');

          if ($startDate == '' && $endDate == '') {
             $laporanpengajuan = Pengajuan::paginate(10);
         } else {
             $laporanpengajuan = Pengajuan::whereDate('tanggal','>=',$startDate)
                                         ->whereDate('tanggal','<=',$endDate)
                                         ->paginate(10);
         }
         session(['filter_start_date' => $startDate]);
         session(['filter_end_date' => $endDate]);

         return view('laporannya.laporanpengajuan', compact('laporanpengajuan'));
     }


     public function laporanpengajuanpdf(Request $request )
     {
         $startDate = session('filter_start_date');
         $endDate = session('filter_end_date');

         if ($startDate == '' && $endDate == '') {
             $laporanpengajuan = Pengajuan::all();
         } else {
             $laporanpengajuan = Pengajuan::whereDate('tanggal', '>=', $startDate)
                                             ->whereDate('tanggal', '<=', $endDate)
                                             ->get();
         }

         // Render view dengan menyertakan data laporan dan informasi filter
         $pdf = PDF::loadview('laporannya.laporanpengajuanpdf', compact('laporanpengajuan'));
         return $pdf->download('laporan_laporanpengajuan.pdf');
     }


     //Approval Status
    public function updateStatusPengajuan(Request $request, $id)
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
        return redirect()->route('pengajuan.index')->with('success', 'Status surat berhasil diperbarui.');
    }



    // Report Pernama
    public function pernama(Request $request)
{
    // Ambil filter dari request, defaultnya adalah null
    $filter = $request->query('filter', null);

    // Ambil data pengajuan berdasarkan filter
    if ($filter === 'all' || empty($filter)) {
        $pengajuan = Pengajuan::paginate(10);
    } else {
        $pengajuan = Pengajuan::where('id_daerah', $filter)->paginate(10);
    }

    // Ambil data agregat
    $idAnggotaCounts = Pengajuan::select('id_daerah', DB::raw('count(*) as count'))
        ->groupBy('id_daerah')
        ->orderBy('id_daerah')
        ->get();

    // Ambil data master anggota
    $masterdaerah = Masterdaerah::all();

    return view('laporannya.pernama', [
        'pengajuan' => $pengajuan,
        'idAnggotaCounts' => $idAnggotaCounts,
        'filter' => $filter,
        'masterdaerah' => $masterdaerah,
    ]);
}

    // Fungsi untuk mencetak PDF
    public function cetakPernamaPdf(Request $request)
{
    $filter = $request->query('filter', null);

    // Handle filtering
    if ($filter === 'all' || empty($filter)) {
        $pengajuan = Pengajuan::all();
    } else {
        $pengajuan = Pengajuan::where('id_daerah', $filter)->get();
    }

    // Get aggregated data
    $idAnggotaCounts = Pengajuan::groupBy('id_daerah')
        ->orderBy('id_daerah')
        ->select(DB::raw('count(*) as count, id_daerah'))
        ->get();

    // Load view and convert to PDF
    $pdf = PDF::loadView('laporannya.pernamapdf', [
        'pengajuan' => $pengajuan,
        'idAnggotaCounts' => $idAnggotaCounts,
        'filter' => $filter,
    ]);

    // Return the generated PDF as a download
    return $pdf->download('laporan_daerah_penerima.pdf');
}

}
