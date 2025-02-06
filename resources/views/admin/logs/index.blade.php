@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2><i class="bi bi-clock-history"></i> Riwayat Aktivitas</h2>

    <!-- Filter -->
    <form method="GET" action="{{ route('admin.logs.index') }}" class="mb-3">
        <label for="action">Filter berdasarkan aksi:</label>
        <select name="action" id="action" class="form-select" onchange="this.form.submit()">
            <option value="">Semua</option>
            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
            <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
            <option value="tambah" {{ request('action') == 'tambah' ? 'selected' : '' }}>Tambah</option>
            <option value="edit" {{ request('action') == 'edit' ? 'selected' : '' }}>Edit</option>
            <option value="hapus" {{ request('action') == 'hapus' ? 'selected' : '' }}>Hapus</option>
        </select>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Petugas</th>
                <th>Aksi</th>
                <th>Model</th>
                <th>Model ID</th>
                <th>IP Address</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $log->petugas->nama_petugas ?? 'Tidak Diketahui' }}</td>
                <td>
                    @if($log->action == 'login')
                        <span class="badge bg-success">Login</span>
                    @elseif($log->action == 'logout')
                        <span class="badge bg-danger">Logout</span>
                    @elseif($log->action == 'tambah')
                        <span class="badge bg-primary">Tambah</span>
                    @elseif($log->action == 'edit')
                        <span class="badge bg-warning">Edit</span>
                    @elseif($log->action == 'hapus')
                        <span class="badge bg-danger">Hapus</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($log->action) }}</span>
                    @endif
                </td>
                <td>{{ $log->model ?? '-' }}</td>
                <td>{{ $log->model_id ?? '-' }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $logs->links() }}
    </div>
</div>
@endsection
