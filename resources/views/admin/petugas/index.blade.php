@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">Manajemen Petugas</h1>

    <!-- Form Tambah Petugas -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            Tambah Petugas
        </div>
        <div class="card-body">
            <form action="{{ route('admin.petugas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Petugas</label>
                    <input type="text" name="nama_petugas" class="form-control" placeholder="Masukkan nama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="petugas">Petugas</option>
                        <option value="kasir">Kasir</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Tambah Petugas</button>
            </form>
        </div>
    </div>

    <!-- Tabel Petugas -->
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
