<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Penyetopan;
use App\Models\Masterdaerah;
use Illuminate\Http\Request;

class PenyetopanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $penyetopan = Penyetopan::where('nosurat', 'LIKE', '%' . $request->search . '%')->paginate(10);
        } else {
            $penyetopan = Penyetopan::paginate(10);
        }
        return view('penyetopan.index', [
            'penyetopan' => $penyetopan,
        ]);
    }

    public function create()
    {
        $masterdaerah = Masterdaerah::all();
        return view('penyetopan.create', [
            'masterdaerah' => $masterdaerah,
        ]);
        return view('penyetopan.create')->with('success', 'Data Telah ditambahkan');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_daerah' => 'required|string',
            'keterangan' => 'required|string',
            'jumlahdana' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        // Generate kode surat
        $nosurat = $this->generatenosurat();

        // Ambil data dari request dan tambahkan kode surat
        $data = $request->all(['id_daerah', 'id_pegawai', 'keterangan', 'jumlahdana', 'tanggal', 'penanggungjawab']);
        $data['nosurat'] = $nosurat;

        // Menyimpan data ke database
        Penyetopan::create($data);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('penyetopan.index')->with('success', 'Data telah ditambahkan');
    }

    public function generatenosurat()
    {
        // Mendapatkan surat terakhir berdasarkan tanggal
        $latestSurat = Penyetopan::orderBy('created_at', 'desc')->first();

        // Menangani kasus jika belum ada surat yang tersimpan
        if (!$latestSurat) {
            return 'SRT-PNYTP-001';
        }

        // Mendapatkan nomor surat terakhir dan increment
        $lastKode = $latestSurat->nosurat;
        $lastNumber = (int) substr($lastKode, -3);
        $newNumber = $lastNumber + 1;

        // Generate kode surat baru
        $newKode = 'SRT-PNYTP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return $newKode;
    }

    public function show($id) {}

    public function edit(Penyetopan $penyetopan)
    {
        $masterdaerah = Masterdaerah::all();

        return view('penyetopan.edit', [
            'item' => $penyetopan,
            'masterdaerah' => $masterdaerah,
        ]);
    }

    public function update(Request $request, Penyetopan $penyetopan)
    {
        $data = $request->all();

        $penyetopan->update($data);

        //dd($data);

        return redirect()->route('penyetopan.index')->with('success', 'Data Telah diupdate');
    }

    public function destroy(Penyetopan $penyetopan)
    {
        $penyetopan->delete();
        return redirect()->route('penyetopan.index')->with('success', 'Data Telah dihapus');
    }

    public function updateStatuspenyetopan(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Terverifikasi,Ditolak',
        ]);

        // Find the rawatrumahkaca entry by ID
        $penyetopan = Penyetopan::findOrFail($id);

        // Update the status based on the form input
        $penyetopan->status = $validated['status'];
        $penyetopan->save();

        // Redirect back to the suratmasuk page with a success message
        return redirect()->route('penyetopan.index')->with('success', 'Status surat berhasil diperbarui.');
    }

    // Laporan Buku Surat Pusat Filter
    public function cetakpenyetopanpertanggal()
    {
        $penyetopan = Penyetopan::Paginate(10);

        return view('laporannya.laporanpenyetopan', ['laporanpenyetopan' => $penyetopan]);
    }

    public function filterdatepenyetopan(Request $request)
    {
        $startDate = $request->input('dari');
        $endDate = $request->input('sampai');

        if ($startDate == '' && $endDate == '') {
            $laporanpenyetopan = Penyetopan::paginate(10);
        } else {
            $laporanpenyetopan = Penyetopan::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->paginate(10);
        }
        session(['filter_start_date' => $startDate]);
        session(['filter_end_date' => $endDate]);

        return view('laporannya.laporanpenyetopan', compact('laporanpenyetopan'));
    }

    public function laporanpenyetopanpdf(Request $request)
    {
        $startDate = session('filter_start_date');
        $endDate = session('filter_end_date');

        // Mengambil data laporan berdasarkan filter tanggal
        if ($startDate == '' && $endDate == '') {
            $laporanpenyetopan = Penyetopan::all();
        } else {
            $laporanpenyetopan = Penyetopan::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->get();
        }

        // Mengambil data pengguna yang sedang login
        $user = auth()->user();

        // Menghitung Grand Total
        $grandTotal = $laporanpenyetopan->sum('jumlahdana');

        // Render view dengan menyertakan data laporan, grand total, dan informasi pengguna
        $pdf = PDF::loadview('laporannya.laporanpenyetopanpdf', compact('laporanpenyetopan', 'user', 'grandTotal'));

        // Mengunduh file PDF
        return $pdf->download('laporan_laporanpenyetopan.pdf');
    }
}
