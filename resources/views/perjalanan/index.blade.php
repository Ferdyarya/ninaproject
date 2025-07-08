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
                            <h1 class="m-0">Data Surat Perjalanan Dinas</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Data Surat Perjalanan Dinas</li>
                            </ol>
                        </div>
                    </div>
            </div>

            <div class="container">
                {{-- search --}}
                <div class="row g-3 align-items-center mb-4">
                    <div class="col-auto">
                        <form action="perjalanan" method="GET">
                            <input type="text" id="search" name="search" class="form-control" placeholder="Search">
                        </form>
                    </div>
                    @if (Auth::user()->hakakses('petugas')|| Auth::user()->hakakses('admin'))
                    <div class="col-auto">
                        <a href="{{ route('perjalanan.create')}}" class="btn btn-success">
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
                                <th class="px-6 py-2">Tanggal Perjalanan</th>
                                <th class="px-6 py-2">Arah Tujuan</th>
                                <th class="px-6 py-2">Alamat Tujuan</th>
                                {{-- <th class="px-6 py-2">Pegawai Berangkat</th> --}}
                                <th class="px-6 py-2">Deskripsi</th>
                                <th class="px-6 py-2">Perihal</th>
                                <th class="px-6 py-2">Status</th>
                                @if (Auth::user()->hakakses('petugas')|| Auth::user()->hakakses('admin'))
                                <th class="px-6 py-2">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no=1;
                            @endphp
                            @foreach ($perjalanan as $index => $item)
                            <tr>
                                <th class="px-6 py-2">{{ $index + $perjalanan->firstItem() }}</th>
                                <td class="px-6 py-2">{{ $item->nosurat }}</td>
                                <td class="px-6 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td class="px-6 py-2">{{ $item->masterdaerah->namadaerah }}</td>
                                <td class="px-6 py-2">{{ $item->masterdaerah->alamat }}</td>
                                {{-- <td class="px-6 py-2">{{ $item->masterpegawai->nama }}</td> --}}
                                <td class="px-6 py-2">{{ $item->deskripsi }}</td>
                                <td class="px-6 py-2">{{ $item->perihal }}</td>
                                <td class="px-6 py-2">
                                    <!-- Menampilkan status jika sudah Arsipkan -->
                                    @if($item->status == 'Arsipkan')
                                        <span class="p-2 mb-2 bg-success text-black rounded">Arsipkan</span>
                                    @else
                                        <!-- Form untuk mengubah status menjadi Arsipkan -->
                                        <form action="{{ route('updateStatusperjalanan', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                Arsipkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                @if (Auth::user()->hakakses('petugas')|| Auth::user()->hakakses('admin'))
                                <td class="px-6 py-2">
                                    <a href="{{ route('perjalanan.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('perjalanan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                    <a href="{{ route('perjalanan.detail', $item->id) }}" class="mt-1 btn btn-primary">
                                                    Detail
                                                </a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $perjalanan->links() }}
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
    @if(Session::has('success'))
toastr.success("{{ Session::get('success')}}")
@endif
</script>
@endpush
