@extends('layout.admin')

@section('content')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />

    <!-- Select2 CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <title>Surat Perjalanan Dinas</title>


    <body>
        <div class="container-fluid">
            <div class="card" style="border-radius: 15px;">
                <div class="card-body">
                    <h1 class="text-center mb-4">Tambah Data Surat Perjalanan Dinas</h1>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-8">
                                <div class="card" style="border-radius: 10px;">
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('perjalanan.store') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <label for="id_daerah">Daerah Tujuan</label>
                                                <select class="form-select" name="id_daerah" id="daerah"
                                                    style="border-radius: 8px;" data-placeholder="Pilih Daerah Tujuan">
                                                    <option></option>
                                                    @foreach ($masterdaerah as $item)
                                                        <option value="{{ $item->id }}">{{ $item->namadaerah }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="tanggal">Tanggal Berangkat</label>
                                                <input type="date" name="tanggal"
                                                    class="form-control @error('tanggal') is-invalid @enderror"
                                                    id="tanggal" value="{{ old('tanggal') }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="form-group">
                                                <label for="perihal">Perihal</label>
                                                <select name="perihal" id="perihal"
                                                    class="form-control @error('perihal') is-invalid @enderror" required>
                                                    <option value="">-- Pilih Perihal Perjalanan --</option>
                                                    <option value="Sosialisasi Pajak Air Permukaan"
                                                        {{ old('perihal') == 'Sosialisasi Pajak Air Permukaan' ? 'selected' : '' }}>
                                                        Sosialisasi Pajak Air Permukaan</option>
                                                    <option value="Verifikasi Data Pajak Kendaraan"
                                                        {{ old('perihal') == 'Verifikasi Data Pajak Kendaraan' ? 'selected' : '' }}>
                                                        Verifikasi Data Pajak Kendaraan</option>
                                                    <option value="Evaluasi Penerimaan Pajak Bahan Bakar"
                                                        {{ old('perihal') == 'Evaluasi Penerimaan Pajak Bahan Bakar' ? 'selected' : '' }}>
                                                        Evaluasi Penerimaan Pajak Bahan Bakar</option>
                                                    <option value="Rapat Koordinasi Pendapatan Daerah"
                                                        {{ old('perihal') == 'Rapat Koordinasi Pendapatan Daerah' ? 'selected' : '' }}>
                                                        Rapat Koordinasi Pendapatan Daerah</option>
                                                    <option value="Pembinaan Pegawai UPTD"
                                                        {{ old('perihal') == 'Pembinaan Pegawai UPTD' ? 'selected' : '' }}>
                                                        Pembinaan Pegawai UPTD</option>
                                                    <option value="Rapat Evaluasi PAD"
                                                        {{ old('perihal') == 'Rapat Evaluasi PAD' ? 'selected' : '' }}>
                                                        Rapat Evaluasi PAD</option>
                                                    <option value="Bimbingan Teknis Sistem Pajak Online"
                                                        {{ old('perihal') == 'Bimbingan Teknis Sistem Pajak Online' ? 'selected' : '' }}>
                                                        Bimbingan Teknis Sistem Pajak Online</option>
                                                    <option value="Sosialisasi Kebijakan Pendapatan Daerah"
                                                        {{ old('perihal') == 'Sosialisasi Kebijakan Pendapatan Daerah' ? 'selected' : '' }}>
                                                        Sosialisasi Kebijakan Pendapatan Daerah</option>
                                                    <option value="Bimbingan Teknis Pengelolaan Pajak Daerah"
                                                        {{ old('perihal') == 'Bimbingan Teknis Pengelolaan Pajak Daerah' ? 'selected' : '' }}>
                                                        Bimbingan Teknis Pengelolaan Pajak Daerah</option>
                                                    <option value="Bimtek Implementasi Sistem Informasi Pajak"
                                                        {{ old('perihal') == 'Bimtek Implementasi Sistem Informasi Pajak' ? 'selected' : '' }}>
                                                        Bimtek Implementasi Sistem Informasi Pajak</option>
                                                    <option value="Monitoring dan Evaluasi Samsat Induk dan Keliling"
                                                        {{ old('perihal') == 'Monitoring dan Evaluasi Samsat Induk dan Keliling' ? 'selected' : '' }}>
                                                        Monitoring dan Evaluasi Samsat Induk dan Keliling</option>
                                                    <option value="Monitoring Kinerja UPTD Pendapatan Daerah"
                                                        {{ old('perihal') == 'Monitoring Kinerja UPTD Pendapatan Daerah' ? 'selected' : '' }}>
                                                        Monitoring Kinerja UPTD Pendapatan Daerah</option>
                                                    <option value="Monitoring Realisasi Pendapatan Daerah"
                                                        {{ old('perihal') == 'Monitoring Realisasi Pendapatan Daerah' ? 'selected' : '' }}>
                                                        Monitoring Realisasi Pendapatan Daerah</option>
                                                    <option value="Evaluasi Program Peningkatan Pajak Daerah"
                                                        {{ old('perihal') == 'Evaluasi Program Peningkatan Pajak Daerah' ? 'selected' : '' }}>
                                                        Evaluasi Program Peningkatan Pajak Daerah</option>
                                                </select>
                                                @error('perihal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="form-group">
                                                <label for="deskripsi">Deskripsi</label>
                                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                                    placeholder="Masukkan deskripsi perjalanan" value="{{ old('deskripsi') }}" required></textarea>
                                                @error('deskripsi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <hr>
                                            <h5 class="mt-4">Partisipan</h5>
                                            <div id="partisipan-wrapper">
                                                <div class="form-row mb-2 align-items-center">
                                                    <div class="col">
                                                        <select name="partisipan[0][id_masterpegawai]"
                                                            class="form-select select-pegawai" required>
                                                            <option value="">Pilih Pegawai</option>
                                                            @foreach ($masterpegawai as $pegawai)
                                                                <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-row">❌</button>
                                                    </div>
                                                </div>
                                            </div>


                                            <button type="button" class="btn btn-sm btn-success mb-3"
                                                onclick="tambahPartisipan()">+ Tambah Partisipan</button>


                                            <hr>
                                            <h5 class="mt-4">Anggaran</h5>
                                            <div id="anggaran-wrapper">
                                                <div class="form-row mb-2 align-items-center">
                                                    <div class="col">
                                                        <input type="text" name="anggaran[0][keterangan]"
                                                            class="form-control" placeholder="Keterangan">
                                                    </div>
                                                    <div class="col">
                                                        <input type="number" name="anggaran[0][anggaran]"
                                                            class="form-control" placeholder="Jumlah Anggaran">
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-row">❌</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-success mb-3"
                                                onclick="tambahAnggaran()">+ Tambah Anggaran</button>


                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>


























    <!-- Optional JavaScript Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV7YyybLOtiN6bX3h+rXxy5lVX" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+pyRy4IhBQvqo8Rx2ZR1c8KRjuva5V7x8GA" crossorigin="anonymous">
    </script>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $('#judulbuku').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $('#judulbuku').data('placeholder')
        });

        $('#daerah').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $('#daerah').data('placeholder')
        });
    </script>

    <script>
        let i = 1;

        function tambahPartisipan() {
            let options = `<option value="">Pilih Pegawai</option>`;
            pegawaiList.forEach(p => {
                options += `<option value="${p.id}">${p.nama}</option>`;
            });

            $('#partisipan-wrapper').append(`
        <div class="form-row mb-2 align-items-center">
            <div class="col">
                <select name="partisipan[${i}][id_masterpegawai]" class="form-select select-pegawai" required>
                    ${options}
                </select>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-danger btn-sm remove-row">❌</button>
            </div>
        </div>
    `);

            // Apply select2 ke elemen yang baru
            $('.select-pegawai').select2({
                theme: 'bootstrap-5'
            });

            i++;
        }


        let j = 1;

        function tambahAnggaran() {
            $('#anggaran-wrapper').append(`
        <div class="form-row mb-2 align-items-center">
            <div class="col">
                <input type="text" name="anggaran[${j}][keterangan]" class="form-control" placeholder="Keterangan">
            </div>
            <div class="col">
                <input type="number" name="anggaran[${j}][anggaran]" class="form-control" placeholder="Jumlah Anggaran">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-danger btn-sm remove-row">❌</button>
            </div>
        </div>
    `);
            j++;
        }

        // Hapus baris
        $(document).on('click', '.remove-row', function() {
            $(this).closest('.form-row').remove();
        });
    </script>

    <script>
        const pegawaiList = @json($masterpegawai);

        $(document).ready(function() {
            $('.select-pegawai').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
@endsection
