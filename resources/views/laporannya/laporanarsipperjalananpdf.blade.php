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
        <h5 class="mt-4">Rekap Laporan Surat Diarsipkan</h5>
    </center>



    <br>

    <table class='table table-bordered' id="warnatable">
        <thead>
            <tr>
                <th class="px-6 py-2">No</th>
                <th class="px-6 py-2">Nomor Surat</th>
                <th class="px-6 py-2">Tanggal laporanperjalanan</th>
                <th class="px-6 py-2">Arah Tujuan</th>
                <th class="px-6 py-2">Alamat Tujuan</th>
                {{-- <th class="px-6 py-2">Pegawai Berangkat</th> --}}
                <th class="px-6 py-2">Deskripsi</th>
                <th class="px-6 py-2">Perihal</th>
                <th class="px-6 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- @php
            $grandTotal = 0;
            @endphp --}}

            @foreach ($laporanarsipperjalanan as $item)
                <tr>
                    <td class="px-6 py-6">{{ $loop->iteration }}</td>
                    <td class="px-6 py-2">{{ $item->nosurat }}</td>
                    <td class="px-6 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-2">{{ $item->masterdaerah->namadaerah }}</td>
                    <td class="px-6 py-2">{{ $item->masterdaerah->alamat }}</td>
                    {{-- <td class="px-6 py-2">{{ $item->masterpegawai->nama }}</td> --}}
                    <td class="px-6 py-2">{{ $item->deskripsi }}</td>
                    <td class="px-6 py-2">{{ $item->perihal }}</td>
                    <td class="px-6 py-2">
                        @if ($item->status == 'Arsipkan')
                            <span class="p-2 mb-2 bg-success text-black rounded">Arsipkan</span>
                        @elseif($item->status == 'Ditolak')
                            <span class="p-2 mb-2 bg-danger text-black rounded">Ditolak</span>
                        @endif
                    </td>
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
