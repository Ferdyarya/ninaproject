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
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Data Surat Penyetopan Anggaran Daerah</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Data Surat Penyetopan Anggaran Daerah</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        {{-- search --}}
                        <div class="row g-3 align-items-center mb-4">
                            <div class="col-auto">
                                <form action="penyetopan" method="GET">
                                    <input type="text" id="search" name="search" class="form-control"
                                        placeholder="Search">
                                </form>
                            </div>
                            @if (Auth::user()->hakakses('petugas')|| Auth::user()->hakakses('admin'))
                            <div class="col-auto">
                                <a href="{{ route('penyetopan.create') }}" class="btn btn-success">
                                    Tambah Data
                                </a>
                            </div>
                            @endif
                        </div>
                        <div>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-2">No</th>
                                        <th class="px-6 py-2">Nomor Surat</th>
                                        <th class="px-6 py-2">Tanggal</th>
                                        <th class="px-6 py-2">Daerah</th>
                                        <th class="px-6 py-2">Jumlah Dana</th>
                                        <th class="px-6 py-2">Keterangan</th>
                                        <th class="px-6 py-2">Status</th>
                                        @if (Auth::user()->hakakses('petugas')|| Auth::user()->hakakses('admin'))
                                        <th class="px-6 py-2">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($penyetopan as $index => $item)
                                        <tr>
                                            <th class="px-6 py-2">{{ $index + $penyetopan->firstItem() }}</th>
                                            <td class="px-6 py-2">{{ $item->nosurat }}</td>
                                            <td class="px-6 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                            <td class="px-6 py-2">{{ $item->masterdaerah->namadaerah }}</td>
                                            <td class="px-6 py-2">Rp. {{ number_format($item->jumlahdana) }}</td>
                                            <td class="px-6 py-2">{{ $item->keterangan }}</td>
                                            <td class="px-6 py-2">
                                                @if($item->status == 'Terverifikasi')
                                                <span class="p-2 mb-2 bg-success text-black rounded">Terverifikasi</span> <!-- Green for verified -->
                                                @elseif($item->status == 'Ditolak')
                                                <span class="p-2 mb-2 bg-danger text-black rounded">Ditolak</span> <!-- Red/orange for rejected -->
                                                @else
                                               @if (Auth::user()->hakakses('petugas')|| Auth::user()->hakakses('admin'))
                                                <span class="p-2 mb-2 bg-warning text-black rounded">Tunggu Verifikasi</span>
                                                @endif
                                                @if (Auth::user()->hakakses('pimpinan'))
                                                    <form action="{{ route('updateStatuspenyetopan', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="status" class="form-control form-control-sm">
                                                            <option value="Terverifikasi" {{ $item->status == 'Terverifikasi' ? 'selected' : '' }} style="background-color: #28a745; color: white;">Verifikasi</option> <!-- Green for Verifikasi -->
                                                            <option value="Ditolak" {{ $item->status == 'Ditolak' ? 'selected' : '' }} style="background-color: #dc3545; color: white;">Tolak</option> <!-- Red for Ditolak -->
                                                        </select>
                                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Update Status</button>
                                                    </form>
                                                @endif
                                            </td>
                                            @endif
                                            @if (Auth::user()->hakakses('petugas')|| Auth::user()->hakakses('admin'))
                                            <td>
                                                <a href="{{ route('penyetopan.edit', $item->id) }}" class="btn btn-primary">
                                                    Edit
                                                </a>
                                                <form action="{{ route('penyetopan.destroy', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $penyetopan->links() }}
                        </div>
                    </div>
                </div>
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
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}")
        @endif
    </script>
@endpush
