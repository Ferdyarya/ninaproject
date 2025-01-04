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
                    <h4>BADAN PENDAPATAN DAERAH PROVINSI KALIMANTAN SELATAN</h4>
                    <p>Jl. Pangeran Suriansyah No.9, Loktabat Utara, Kec. Banjarbaru Utara, Kota Banjar Baru, Kalimantan Selatan</p>
                </td>
            </tr>
        </table>
    </div>

    <center>
        <h5 class="mt-4">Rekap Laporan Surat Perjalanan Dinas</h5>
    </center>



    <br>

    <table class='table table-bordered' id="warnatable">
        <thead>
            <tr>
                <th class="px-6 py-2">No</th>
                <th class="px-6 py-2">Nomor Surat</th>
                <th class="px-6 py-2">Tanggal Perjalanan</th>
                <th class="px-6 py-2">Arah Tujuan</th>
                <th class="px-6 py-2">Alamat Tujuan</th>
                <th class="px-6 py-2">Pegawai Berangkat</th>
                <th class="px-6 py-2">Deskripsi</th>
                <th class="px-6 py-2">Perihal</th>
            </tr>
        </thead>
        <tbody>
            {{-- @php
            $grandTotal = 0;
            @endphp --}}

            @foreach ($laporanperjalanan as $item)
                <tr>
                    <td class="px-6 py-6">{{ $loop->iteration }}</td>
                    <td class="px-6 py-2">{{ $item->nosurat }}</td>
                    <td class="px-6 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-2">{{ $item->masterdaerah->namadaerah }}</td>
                    <td class="px-6 py-2">{{ $item->masterdaerah->alamat }}</td>
                    <td class="px-6 py-2">{{ $item->masterpegawai->nama }}</td>
                    <td class="px-6 py-2">{{ $item->deskripsi }}</td>
                    <td class="px-6 py-2">{{ $item->perihal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="date-container">
        Banjarbaru, <span class="formatted-date">{{ now()->format('d-m-Y') }}</span>
    </div>
    <p class="signature">(Pimpinan)</p>
</body>

</html>