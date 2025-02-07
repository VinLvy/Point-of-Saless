@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Manajemen Petugas</h1>
    
    <div class="mb-3">
        <a href="{{ route('admin.petugas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Petugas
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            Daftar Petugas
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($petugas as $p)
                            <tr>
                                <td>{{ $p->nama_petugas }}</td>
                                <td>{{ $p->email }}</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($p->role) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.petugas.edit', $p->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.petugas.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($petugas->isEmpty())
                <p class="text-center text-muted">Belum ada petugas yang terdaftar.</p>
            @endif
        </div>
    </div>
</div>
@endsection