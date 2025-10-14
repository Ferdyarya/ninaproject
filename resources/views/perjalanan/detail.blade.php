@extends('layout.admin')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .table th, .table td {
        vertical-align: middle !important;
    }

    .grand-total {
        background-color: #f8f9fa;
        border-top: 2px solid #343a40;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .section-title {
        font-weight: 600;
        color: #343a40;
        border-left: 4px solid #28a745;
        padding-left: 10px;
        margin-top: 20px;
    }

    .summary-box {
        background: #f0f7f3;
        border-left: 4px solid #28a745;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .summary-box h5 {
        color: #155724;
        font-weight: 600;
    }

    .summary-box p {
        margin-bottom: 4px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="content-wrapper">
                <div class="content-header mb-4">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-success">Detail Data Surat Perjalanan Dinas</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Detail Data</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <h5 class="section-title">Daftar Biaya & Anggaran</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Pegawai Berangkat</th>
                                    <th>Rincian Biaya Jabatan</th>
                                    <th>Rincian Anggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalJabatan = 0;
                                    $totalAnggaran = 0;
                                    $budgetPerjalanan = $perjalanan->masterdaerah->budgetperjalanan ?? 0;
                                @endphp

                                <tr>
                                    <td>1</td>
                                    <td>
                                        <ul class="list-unstyled">
                                            @foreach ($perjalanan->partisipan as $p)
                                                <li><strong>{{ $p->masterpegawai->nama ?? 'N/A' }}</strong></li>
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td>
                                        <ul class="list-unstyled">
                                            @foreach ($perjalanan->partisipan as $p)
                                                @php
                                                    $biaya = $p->masterpegawai->masterpangkat->biaya ?? 0;
                                                    $totalJabatan += $biaya;
                                                @endphp
                                                <li>Rp{{ number_format($biaya, 0, ',', '.') }}</li>
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td>
                                        <ul class="list-unstyled">
                                            @foreach ($perjalanan->anggaran as $a)
                                                @php
                                                    $totalAnggaran += $a->anggaran;
                                                @endphp
                                                <li>{{ $a->keterangan }} = Rp{{ number_format($a->anggaran, 0, ',', '.') }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>

                                {{-- Total Baris --}}
                                <tr class="grand-total">
                                    <td colspan="2" class="text-center">TOTAL BIAYA JABATAN</td>
                                    <td colspan="2">Rp{{ number_format($totalJabatan, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="grand-total">
                                    <td colspan="2" class="text-center">TOTAL ANGGARAN</td>
                                    <td colspan="2">Rp{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="grand-total">
                                    <td colspan="2" class="text-center">BUDGET PERJALANAN</td>
                                    <td colspan="2">Rp{{ number_format($budgetPerjalanan, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="grand-total bg-success text-white">
                                    <td colspan="2" class="text-center">GRAND TOTAL KESELURUHAN</td>
                                    <td colspan="2">Rp{{ number_format($totalJabatan + $totalAnggaran + $budgetPerjalanan, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div> <!-- end container -->
            </div>
        </div>
    </div>
</div>
@endsection
