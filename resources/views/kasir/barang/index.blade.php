@extends('layouts.kasir')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-box"></i> Daftar Barang</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="GET" action="{{ route('kasir.barang.index') }}" class="mb-3">
                <div class="d-flex flex-column gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari barang..." value="{{ request('search') }}">
            
                    <select name="kategori" class="form-select select2" style="width: 100%;">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
            
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                </div>
            </form>            

            <div class="table-responsive">
                <table class="table table-hover table-bordered rounded-3 overflow-hidden">
                    <thead class="table-dark text-center text-white">
                        <tr>
                            <th>No.</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual 1</th>
                            <th>Harga Jual 2</th>
                            <th>Harga Jual 3</th>
                            <th>Minimal Stok</th>
                            <th>Stok</th>
                            <th>Buy Date</th>
                            <th>Exp Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $index => $item)
                            @php
                                $totalStok = $item->stok->sum('jumlah_stok');
                                $expDateTercepat = optional($item->stok->sortBy('expired_date')->first())->expired_date ? date('d M Y', strtotime($item->stok->sortBy('expired_date')->first()->expired_date)) : '-';
                                $buyDateTerlama = optional($item->stok->sortBy('buy_date')->first())->buy_date ? date('d M Y', strtotime($item->stok->sortBy('buy_date')->first()->buy_date)) : '-';
                            @endphp
                            <tr>
                                <td class="text-center">{{ $barang->firstItem() + $index }}</td>
                                <td class="fw-bold">{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->kategori->nama_kategori }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_jual_1, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_jual_2, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_jual_3, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->minimal_stok }}</td>
                                <td class="text-center {{ $totalStok < $item->minimal_stok ? 'text-danger fw-bold' : '' }}">
                                    {{ $totalStok }}
                                </td>
                                <td class="text-center">{{ $buyDateTerlama }}</td>
                                <td class="text-center">{{ $expDateTercepat }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted">Belum ada data barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>            

            <div class="d-flex justify-content-center mt-3 d-print-none">
                {{ $barang->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        let alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);

    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih Kategori",
            allowClear: true
        });
    });
</script>
@endsection
