@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3"><i class="bi bi-info-circle"></i> Detail Riwayat Aktivitas</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold">Petugas : {{ $log->petugas->nama_petugas ?? 'Tidak Diketahui' }}</h5>
            <p><strong>Aksi :</strong> <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($log->action) }}</span></p>
            <p><strong>Model :</strong> {{ ucwords(str_replace('_', ' ', $log->model)) }}</p>
            <p><strong>IP Address :</strong> {{ $log->ip_address }}</p>
            <p><strong>Waktu :</strong> {{ $log->created_at->format('d-m-Y H:i:s') }}</p>
            
            <p><strong>Perubahan Data :</strong></p>

            @php
                $changes = array_diff_assoc($log->new_data ?? [], $log->old_data ?? []);
            @endphp

            @if (!empty($changes))
                <table class="table table-bordered mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>Kolom</th>
                            <th>Data Lama</th>
                            <th>Data Baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($changes as $key => $newValue)
                            <tr>
                                <td class="fw-bold">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                <td>{{ $log->old_data[$key] ?? '-' }}</td>
                                <td class="text-success">{{ $newValue }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">Tidak ada perubahan data.</p>
            @endif

            <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
