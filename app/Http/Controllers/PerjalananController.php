<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Perjalanan;
use App\Models\Masterdaerah;
use Illuminate\Http\Request;
use App\Models\Masterpegawai;

class PerjalananController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $perjalanan = Perjalanan::whereHas('masterpegawai', function ($query) use ($request) {
                $query->where('nama', 'LIKE', '%' . $request->search . '%');
            })
                ->orWhere('nosurat', 'LIKE', '%' . $request->search . '%')
                ->paginate(10);
        } else {
            $perjalanan = Perjalanan::paginate(10);
        }

        return view('perjalanan.index', [
            'perjalanan' => $perjalanan,
        ]);
    }

    public function create()
    {
        $masterpegawai = Masterpegawai::all();
        $masterdaerah = Masterdaerah::all();

        return view('perjalanan.create', [
            'masterpegawai' => $masterpegawai,
            'masterdaerah' => $masterdaerah,
        ]);
        return view('perjalanan.create')->with('success', 'Data Telah ditambahkan');
    }

    public function store(Request $request)
    {
        // Validasi permintaan untuk memastikan 'id_daerah' ada
        $request->validate([
            'id_daerah' => 'required|string',
            'id_pegawai' => 'required|string',
            'deskripsi' => 'required|string',
            'perihal' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        // Generate kode surat
        $nosurat = $this->generatenosurat();

        // Ambil data dari request dan tambahkan kode surat
        $data = $request->only(['id_daerah', 'id_pegawai', 'deskripsi', 'perihal', 'tanggal']);
        $data['nosurat'] = $nosurat;

        // Menyimpan data ke database
        Perjalanan::create($data);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('perjalanan.index')->with('success', 'Data telah ditambahkan');
    }

    public function generatenosurat()
    {
        // Ambil data surat terbaru berdasarkan waktu pembuatan
        $latestSurat = Perjalanan::orderBy('created_at', 'desc')->first();

        // Jika tidak ada data surat, mulai dari kode '001'
        if (!$latestSurat) {
            return 'SRT-JL-BJB-001';
        }

        // Ambil nomor terakhir dari kode surat
        $lastKode = $latestSurat->nosurat;
        $lastNumber = (int) substr($lastKode, -3); // Ambil angka terakhir dari kode surat
        $newNumber = $lastNumber + 1;

        // Membuat kode surat baru dengan format yang sesuai
        $newKode = 'SRT-JL-BJB-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return $newKode;
    }

    public function show($id) {}

    public function edit(Perjalanan $perjalanan)
    {
        $masterpegawai = Masterpegawai::all();
        $masterdaerah = Masterdaerah::all();

        return view('perjalanan.edit', [
            'item' => $perjalanan,
            'masterdaerah' => $masterdaerah,
            'masterpegawai' => $masterpegawai,
        ]);
    }

    public function update(Request $request, Perjalanan $perjalanan)
    {
        $data = $request->all();

        $perjalanan->update($data);

        //dd($data);

        return redirect()->route('perjalanan.index')->with('success', 'Data Telah diupdate');
    }

    public function destroy(Perjalanan $perjalanan)
    {
        $perjalanan->delete();
        return redirect()->route('perjalanan.index')->with('success', 'Data Telah dihapus');
    }
    // Tampilan Surat Arsip
    public function suratArsip(Request $request)
    {
        // Cek apakah ada query search
        $search = $request->get('search');

        // Ambil data surat disposisi dengan pencarian (jika ada) dan status 'Arsipkan' atau 'Tidak Diarsipkan'
        $perjalanan = Perjalanan::when($search, function ($query) use ($search) {
            return $query
                ->where('nmrsurat', 'like', '%' . $search . '%')
                ->orWhere('id_masterdaerah', 'like', '%' . $search . '%')
                ->orWhere('perihal', 'like', '%' . $search . '%');
        })
            ->whereIn('status', ['Arsipkan', 'Tidak Diarsipkan']) // Pastikan menampilkan status Arsipkan dan Tidak Diarsipkan
            ->paginate(10); // Menampilkan 10 data per halaman

        // Mengirim data ke view
        return view('perjalanan.suratarsip', compact('perjalanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi status
        // $validated = $request->validate([
        //     'status' => 'required|in:Arsipkan,Tidak Diarsipkan',
        // ]);

        $perjalanan = Perjalanan::findOrFail($id);

        // Update status
        $perjalanan->status = 'Arsipkan';
        $perjalanan->save(); // Simpan perubahan

        return redirect()->route('suratarsip')->with('success', 'Status surat berhasil diperbarui.');
    }

    // Laporan Buku Perjalanan Filter
    public function cetakarsipertanggal()
    {
        // Query Perjalanan with pagination and return to the view
        $perjalanan = Perjalanan::paginate(10);

        return view('laporannya.laporanarsipperjalanan', ['laporanarsipperjalanan' => $perjalanan]);
    }

    public function filterdatearsip(Request $request)
    {
        // Get start and end dates from the request
        $startDate = $request->input('dari');
        $endDate = $request->input('sampai');

        // If both dates are empty, show all records
        if (empty($startDate) && empty($endDate)) {
            $laporanarsipperjalanan = Perjalanan::paginate(10);
        } else {
            // Filter Perjalanan records based on the dates
            $laporanarsipperjalanan = Perjalanan::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->paginate(10);
        }

        // Store the filter dates in the session for later use
        session(['filter_start_date' => $startDate, 'filter_end_date' => $endDate]);

        return view('laporannya.laporanarsipperjalanan', compact('laporanarsipperjalanan'));
    }

    public function laporanarsipperjalananpdf(Request $request)
    {
        // Retrieve the filter dates from the request
        $startDate = $request->dari;
        $endDate = $request->sampai;

        // If no date filters are provided, return all records
        if (empty($startDate) || empty($endDate)) {
            $laporanarsipperjalanan = Perjalanan::all();
        } else {
            // Filter the records based on the requested dates
            $laporanarsipperjalanan = Perjalanan::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->get();
        }

        // Generate PDF and return the download response
        $pdf = PDF::loadView('laporannya.laporanarsipperjalananpdf', compact('laporanarsipperjalanan'));
        return $pdf->download('laporan_arsip_perjalanan.pdf');
    }

    // Laporan Buku Perjalanan Filter
    public function cetakperjalananpertanggal()
    {
        // Query Perjalanan with pagination and return to the view
        $perjalanan = Perjalanan::paginate(10);

        return view('laporannya.laporanperjalanan', ['laporanperjalanan' => $perjalanan]);
    }

    public function filterdateperjalanan(Request $request)
    {
        // Get start and end dates from the request
        $startDate = $request->input('dari');
        $endDate = $request->input('sampai');

        // If both dates are empty, show all records
        if (empty($startDate) && empty($endDate)) {
            $laporanperjalanan = Perjalanan::paginate(10);
        } else {
            // Filter Perjalanan records based on the dates
            $laporanperjalanan = Perjalanan::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->paginate(10);
        }

        // Store the filter dates in the session for later use
        session(['filter_start_date' => $startDate, 'filter_end_date' => $endDate]);

        return view('laporannya.laporanperjalanan', compact('laporanperjalanan'));
    }

    public function laporanperjalananpdf(Request $request)
    {
        // Retrieve the filter dates from the session
        $startDate = session('filter_start_date');
        $endDate = session('filter_end_date');

        // If no date filters are set, return all records
        if (empty($startDate) && empty($endDate)) {
            $laporanperjalanan = Perjalanan::all();
        } else {
            // Filter the records based on the session dates
            $laporanperjalanan = Perjalanan::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->get();
        }

        // Generate PDF and return the download response
        $pdf = PDF::loadView('laporannya.laporanperjalananpdf', compact('laporanperjalanan'));
        return $pdf->download('laporan_laporanperjalanan.pdf');
    }
}
