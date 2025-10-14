<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 9pt;
        }

        body {
            font-family: arial;

        }

        table {
            border-bottom: 4px solid #000;
            /* padding: 2px */
        }

        .tengah {
            text-align: center;
            line-height: 5px;
        }

        #warnatable th {
            padding-top: 12px;
            padding-bottom: 12px;
            /* text-align: left; */
            background-color: #f8ff20;
            color: rgb(0, 0, 0);
            /* text-align: center; */
        }

        #warnatable tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #warnatable tr:hover {
            background-color: #ddd;
        }

        .textmid {
            /* text-align: center; */
        }

        .signature {
            position: absolute;
            margin-top: 20px;
            text-align: right;
            right: 50px;
            font-size: 14px;
        }

        .date-container {
            font-family: arial;
            text-align: left;
            font-size: 14px;
        }
    </style>

    <div class="rangkasurat">
        <table width="100%">
            <tr>
                <td><img src="{{ public_path('assets/logobjb3.png') }}" alt="logo" width="140px"></td>
                <td class="tengah">
                    <h4><b>BADAN PENDAPATAN DAERAH PROVINSI KALIMANTAN SELATAN</b></h4>
                    <p>Jl. Raya Dharma Praja
                        Pemprov Kalsel, Trikora
                        Banjarbaru, Kalimantan Selatan
                        Kode Pos 70700</p>
                </td>
            </tr>
        </table>
    </div>

    <center>
        <h5 class="mt-4">Rekap Laporan Surat Perjalanan Dinas</h5>
    </center>



    <br>

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
            @foreach ($laporanperjalanan as $index => $item)
                @php
                    $totalJabatan = 0;
                    $totalAnggaran = 0;
                    $budgetPerjalanan = $item->masterdaerah->budgetperjalanan ?? 0;
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>

                    {{-- Pegawai Berangkat --}}
                    <td>
                        <ul class="list-unstyled mb-0">
                            @foreach ($item->partisipan as $p)
                                <li><strong>{{ $p->masterpegawai->nama ?? 'N/A' }}</strong></li>
                            @endforeach
                        </ul>
                    </td>

                    {{-- Biaya Jabatan --}}
                    <td>
                        <ul class="list-unstyled mb-0">
                            @foreach ($item->partisipan as $p)
                                @php
                                    $biaya = $p->masterpegawai->masterpangkat->biaya ?? 0;
                                    $totalJabatan += $biaya;
                                @endphp
                                <li>Rp{{ number_format($biaya, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    </td>

                    {{-- Rincian Anggaran --}}
                    <td>
                        <ul class="list-unstyled mb-0">
                            @foreach ($item->anggaran as $a)
                                @php
                                    $totalAnggaran += $a->anggaran;
                                @endphp
                                <li>{{ $a->keterangan }} = Rp{{ number_format($a->anggaran, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>

                {{-- Total Per Item --}}
                <tr class="table-secondary">
                    <td colspan="2" class="text-center"><strong>TOTAL BIAYA JABATAN</strong></td>
                    <td colspan="2">Rp{{ number_format($totalJabatan, 0, ',', '.') }}</td>
                </tr>
                <tr class="table-secondary">
                    <td colspan="2" class="text-center"><strong>TOTAL ANGGARAN</strong></td>
                    <td colspan="2">Rp{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                </tr>
                <tr class="table-info">
                    <td colspan="2" class="text-center"><strong>BUDGET PERJALANAN</strong></td>
                    <td colspan="2">Rp{{ number_format($budgetPerjalanan, 0, ',', '.') }}</td>
                </tr>
                <tr class="table-success text-white">
                    <td colspan="2" class="text-center"><strong>GRAND TOTAL KESELURUHAN</strong></td>
                    <td colspan="2">
                        <strong>Rp{{ number_format($totalJabatan + $totalAnggaran + $budgetPerjalanan, 0, ',', '.') }}</strong>
                    </td>
                </tr>

                {{-- Separator antar perjalanan --}}
                <tr>
                    <td colspan="4" class="bg-light"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="date-container">
        Banjarbaru, <span class="formatted-date">{{ now()->format('d-m-Y') }}</span>
    </div>

    <div style="text-align: right; margin-top: 30px; margin-right: 50px;">
        <p class="signature" style="position: static; text-align: right;">(KEPALA BAPENDA)</p>
        <img src="{{ public_path('assets/QRCODE.png') }}" alt="Tanda Tangan" style="width: 80px; margin-top: 5px;">
        <div style="height: 20px;"></div>
        <p style="text-align: right; margin-top: 8px;"><b>H. SUBHAN YAUMIL, S.E., M.SI</b></p>
    </div>
</body>

</html>
