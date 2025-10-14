<?php

namespace App\Http\Controllers;

use App\Models\Kerugian;
use App\Models\Masterdaerah;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $pengembalian = Pengembalian::where('nosurat', 'LIKE', '%' . $request->search . '%')->paginate(10);
        } else {
            $pengembalian = Pengembalian::paginate(10);
        }
        return view('pengembalian.index', [
            'pengembalian' => $pengembalian,
        ]);
    }

    public function create()
    {
        $masterdaerah = Masterdaerah::all();
        $kerugian = Kerugian::all();
        return view('pengembalian.create', [
            'masterdaerah' => $masterdaerah,
            'kerugian' => $kerugian,
        ]);
        return view('pengembalian.create')->with('success', 'Data Telah ditambahkan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daerah' => 'required|string',
            'keterangan' => 'required|string',
            'penanggungjawab' => 'required|string',
            'id_kerugian' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $nosurat = $this->generatenosurat();

        $data = $request->all(['id_daerah', 'keterangan', 'id_kerugian', 'tanggal', 'penanggungjawab']);
        $data['nosurat'] = $nosurat;

        Pengembalian::create($data);

        return redirect()->route('pengembalian.index')->with('success', 'Data telah ditambahkan');
    }

    public function generatenosurat()
    {
        // Mendapatkan surat terakhir berdasarkan tanggal
        $latestSurat = Pengembalian::orderBy('created_at', 'desc')->first();

        // Menangani kasus jika belum ada surat yang tersimpan
        if (!$latestSurat) {
            return 'SRT-KMBLI-001';
        }

        // Mendapatkan nomor surat terakhir dan increment
        $lastKode = $latestSurat->nosurat;
        $lastNumber = (int) substr($lastKode, -3);
        $newNumber = $lastNumber + 1;

        // Generate kode surat baru
        $newKode = 'SRT-KMBLI-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return $newKode;
    }

    public function show($id) {}

    public function edit(Pengembalian $pengembalian)
    {
        $masterdaerah = Masterdaerah::all();
        $kerugian = Kerugian::all();

        return view('pengembalian.edit', [
            'item' => $pengembalian,
            'masterdaerah' => $masterdaerah,
            'kerugian' => $kerugian,
        ]);
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        $data = $request->all();

        $pengembalian->update($data);

        //dd($data);

        return redirect()->route('pengembalian.index')->with('success', 'Data Telah diupdate');
    }

    public function destroy(Pengembalian $pengembalian)
    {
        $pengembalian->delete();
        return redirect()->route('pengembalian.index')->with('success', 'Data Telah dihapus');
    }

    // Laporan Buku Surat Pusat Filter
    public function cetakpengembalianpertanggal()
    {
        $pengembalian = Pengembalian::Paginate(10);

        return view('laporannya.laporanpengembalian', ['laporanpengembalian' => $pengembalian]);
    }

    public function filterdatepengembalian(Request $request)
    {
        $startDate = $request->input('dari');
        $endDate = $request->input('sampai');

        if ($startDate == '' && $endDate == '') {
            $laporanpengembalian = Pengembalian::paginate(10);
        } else {
            $laporanpengembalian = Pengembalian::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->paginate(10);
        }
        session(['filter_start_date' => $startDate]);
        session(['filter_end_date' => $endDate]);

        return view('laporannya.laporanpengembalian', compact('laporanpengembalian'));
    }

    public function laporanpengembalianpdf(Request $request)
    {
        $startDate = session('filter_start_date');
        $endDate = session('filter_end_date');

        // Mengambil data laporan berdasarkan filter tanggal
        if ($startDate == '' && $endDate == '') {
            $laporanpengembalian = Pengembalian::all();
        } else {
            $laporanpengembalian = Pengembalian::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->get();
        }

        $user = auth()->user();

        $grandTotal = $laporanpengembalian->sum('jumlahpengembalian');

        $pdf = PDF::loadview('laporannya.laporanpengembalianpdf', compact('laporanpengembalian', 'user', 'grandTotal'));

        // Mengunduh file PDF
        return $pdf->download('laporan_laporanpengembalian.pdf');
    }
}
