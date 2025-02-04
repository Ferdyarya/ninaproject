@extends('layout.admin')

@section('content')
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
      data-sidebar-position="fixed" data-header-position="fixed">

        <div class="container-fluid">
            <!-- Row 1 -->
            <!-- REPORT TODAY -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header card-header-primary">
                                <h3 class="card-title"><b>Laporan Hari Ini</b></h3>
                                {{ $dateNow ? $dateNow->format('d F Y') : 'Tanggal tidak tersedia' }}
                            </div>
                            <div class="row text-center">
                                <div class="col-6 col-md-3">
                                    <h4 class="text-info"><b>Surat Perjalanan Dinas</b></h4>
                                    <h3>{{ $jumlahsuratperjalanan ?? 0 }}</h3>
                                </div>
                                <div class="col-6 col-md-3">
                                    <h4 class="text-info"><b>Surat Yang Arsip</b></h4>
                                    <h3>{{ $jumlahsuratarsip ?? 0 }}</h3>
                                </div>
                                <div class="col-6 col-md-3">
                                    <h4 class="text-info"><b>Surat Pengajuan Dana</b></h4>
                                    <h3>{{ $jumlahsuratpengajuan ?? 0 }}</h3>
                                </div>
                                <div class="col-6 col-md-3">
                                    <h4 class="text-info"><b>Surat Pendapatan Daerah</b></h4>
                                    <h3>{{ $jumlahsuratpendapatan ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Grafik Peminjaman Buku -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><b>Pendapatan Dana Tahunan</b></h3>
                        </div>
                        <div class="card-body">
                            <canvas id="incomeChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('incomeChart').getContext('2d');
                const incomeChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['2023', '2024', '2025'], // Tahun-tahun yang sesuai
                        datasets: [{
                            label: 'Pendapatan Dana',
                            data: [{{ $income2023 }}, {{ $income2024 }}, {{ $income2025 }}], // Data pendapatan aktual untuk tahun 2021, 2022, dan 2023
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>

        </div>
    </div>
@endsection
