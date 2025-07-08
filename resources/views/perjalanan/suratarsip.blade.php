@extends('layout.admin')
@push('css')
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h1>Data Arsip Surat Perjalanan Dinas</h1>

            <div class="row mb-3">
                <div class="col-auto">
                    <a href="{{ route('perjalanan.index') }}" class="btn btn-primary">Kembali ke Data Aktif</a>
                    {{-- <a href="{{ route('laporanarsippdf') }}" class="btn btn-success">Export PDF</a> --}}
                </div>
                <div class="col-auto">
                    <!-- Form Pencarian Arsip -->
                    <form action="Arsipkanpencariannomorsurat" method="GET">
                        <input type="text" name="search" class="form-control" placeholder="Cari Nomor Surat" value="{{ request()->get('search') }}">
                    </form>
                </div>
            </div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="px-6 py-2">No</th>
                        <th class="px-6 py-2">Nomor Surat</th>
                        <th class="px-6 py-2">Tanggal Perjalanan</th>
                        <th class="px-6 py-2">Arah Tujuan</th>
                        <th class="px-6 py-2">Alamat Tujuan</th>
                        {{-- <th class="px-6 py-2">Pegawai Berangkat</th> --}}
                        <th class="px-6 py-2">Deskripsi</th>
                        <th class="px-6 py-2">Perihal</th>
                        <th class="px-6 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perjalanan as $index => $item)
                        <tr>
                            <td class="px-6 py-2">{{ $index + 1 }}</td>
                            <td class="px-6 py-2">{{ $item->nosurat }}</td>
                            <td class="px-6 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td class="px-6 py-2">{{ $item->masterdaerah->namadaerah }}</td>
                            <td class="px-6 py-2">{{ $item->masterdaerah->alamat }}</td>
                            {{-- <td class="px-6 py-2">{{ $item->masterpegawai->nama }}</td> --}}
                            <td class="px-6 py-2">{{ $item->deskripsi }}</td>
                            <td class="px-6 py-2">{{ $item->perihal }}</td>
                            <td class="px-6 py-2">
                                @if($item->status == 'Arsipkan')
                                    <span class="p-2 mb-2 bg-success text-black rounded">Arsipkan</span> <!-- Green for verified -->
                                @elseif($item->status == 'Ditolak')
                                    <span class="p-2 mb-2 bg-danger text-black rounded">Ditolak</span> <!-- Red/orange for rejected -->
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination -->
            {{ $perjalanan->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<script>
    @if(Session::has('success'))
toastr.success("{{ Session::get('success')}}")
@endif
</script>
@endpush

