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

            <form method="GET" action="{{ route('kasir.barang.index') }}" class="mb-3 d-flex gap-2">
                <input type="text" name="search" class="form-control flex-grow-1 h-100" placeholder="Cari barang..." value="{{ request('search') }}">
            
                <select name="kategori" class="form-select select2 h-100" style="min-width: 200px;">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            
                <button type="submit" class="btn btn-primary h-100"><i class="bi bi-search"></i> Cari</button>
            </form>
            

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark text-center text-white">
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual 1</th>
                            <th>Harga Jual 2</th>
                            <th>Harga Jual 3</th>
                            <th>Minimal Stok</th>
                            <th>Stok</th>
                            <th>Exp Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->kategori->nama_kategori }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_jual_1, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_jual_2, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_jual_3, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->minimal_stok }}</td>
                                <td class="text-center {{ $item->stok->sum('jumlah_stok') < $item->minimal_stok ? 'text-danger fw-bold' : '' }}">
                                    {{ $item->stok->sum('jumlah_stok') }}
                                </td>
                                <td>{{ optional($item->stok->sortBy('expired_date')->first())->expired_date ? date('d M Y', strtotime($item->stok->sortBy('expired_date')->first()->expired_date)) : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">Belum ada data barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
