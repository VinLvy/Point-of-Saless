@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3"><i class="bi bi-clock-history"></i> Riwayat Aktivitas</h2>

    <!-- Filter -->
    <form method="GET" action="{{ route('admin.logs.index') }}" class="mb-3">
        <label for="action" class="fw-bold"><i class="bi bi-funnel"></i> Filter berdasarkan aksi:</label>
        <select name="action" id="action" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
            <option value=""> Semua Aksi</option>
            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}> Login</option>
            <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}> Logout</option>
            <option value="tambah" {{ request('action') == 'tambah' ? 'selected' : '' }}> Tambah</option>
            <option value="edit" {{ request('action') == 'edit' ? 'selected' : '' }}> Edit</option>
            <option value="hapus" {{ request('action') == 'hapus' ? 'selected' : '' }}> Hapus</option>
            <option value="transaksi" {{ request('action') == 'transaksi' ? 'selected' : '' }}> Transaksi</option>
        </select>
    </form>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabel -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Petugas</th>
                    <th>Aksi</th>
                    <th>Deskripsi Aktivitas</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-bold">{{ $log->petugas->nama_petugas ?? 'Tidak Diketahui' }}</td>
                    <td>
                        @php
                            $action = $log->action;
                            $badgeClasses = [
                                'login' => 'success',
                                'logout' => 'danger',
                                'tambah' => 'primary',
                                'edit' => 'warning',
                                'hapus' => 'danger',
                                'transaksi' => 'info',
                            ];
                            $badgeClass = $badgeClasses[$action] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badgeClass }} px-3 py-2">
                            {{ ucfirst($action) }}
                        </span>
                    </td>
                    <td>{{ $log->getDeskripsi() }}</td>
                    <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                    <td>
                        <a href="{{ route('admin.logs.show', $log->id) }}" class="btn btn-info btn-sm">Detail</a>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data riwayat aktivitas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $logs->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
    </div>

    <!-- Tombol Hapus 10 Data Terlama -->
    <a href="{{ route('admin.logs.index', ['delete_oldest' => 1]) }}" 
        class="btn btn-danger shadow position-fixed bottom-0 end-0 m-4">
        <i class="bi bi-trash"></i> Hapus 10 Data Terlama
    </a> 

</div>

<script>
    // Fade out alert otomatis setelah 3 detik
    setTimeout(() => {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
</script>

@endsection
