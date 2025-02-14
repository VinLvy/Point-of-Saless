@extends('layouts.kasir')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Selamat Datang, {{ $petugas }}!</h1>

    <!-- Cards Informasi -->
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack text-success" style="font-size: 2rem;"></i>
                    <h5 class="fw-bold mt-2">Total Income Hari Ini</h5>
                    <h3 class="text-success fw-bold">Rp {{ number_format($total_income_hari_ini, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-person-badge text-primary" style="font-size: 2rem;"></i>
                    <h5 class="fw-bold mt-2">Total Petugas</h5>
                    <h3 class="fw-bold">{{ $total_petugas }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people text-warning" style="font-size: 2rem;"></i>
                    <h5 class="fw-bold mt-2">Total Pelanggan</h5>
                    <h3 class="fw-bold">{{ $total_pelanggan }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam text-danger" style="font-size: 2rem;"></i>
                    <h5 class="fw-bold mt-2">Jumlah Barang</h5>
                    <h3 class="fw-bold">{{ $jumlah_barang }}</h3>
                </div>
            </div>
        </div>
    </div>

    @if ($barang_kurang_stok->count() > 0)
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="fw-bold"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Barang dengan Stok Rendah</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-warning">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Stok Saat Ini</th>
                            <th>Minimal Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barang_kurang_stok as $index => $barang)
                            <tr>
                                <td>{{ $barang->nama_barang }}</td>
                                <td class="text-danger fw-bold">{{ $barang->stok->sum('jumlah_stok') }}</td>
                                <td class="fw-bold">{{ $barang->minimal_stok }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Grafik Pendapatan Mingguan -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="fw-bold text-center">Total Pendapatan 1 Minggu Terakhir</h5>
            <canvas id="incomeChart"></canvas>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('incomeChart').getContext('2d');
    var incomeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels_pendapatan),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($data_pendapatan),
                borderColor: 'blue',
                borderWidth: 2,
                fill: false,
            }]
        }
    });
</script>
@endsection
