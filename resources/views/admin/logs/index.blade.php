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

    <!-- Tabel -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Petugas</th>
                    <th>Aksi</th>
                    <th>Deskripsi Aktivitas</th>
                    <th>IP Address</th>
                    <th>Waktu</th>
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
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
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

</div>
@endsection
