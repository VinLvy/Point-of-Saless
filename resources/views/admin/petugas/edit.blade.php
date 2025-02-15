@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">Edit Data Petugas</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            Form Edit Petugas
        </div>
        <div class="card-body">
            <form action="{{ route('admin.petugas.update', $petugas->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Nama Petugas</label>
                    <input type="text" name="nama_petugas" class="form-control" value="{{ old('nama_petugas', $petugas->nama_petugas) }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $petugas->email) }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password (Opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" value="Kasir" disabled>
                    <input type="hidden" name="role" value="kasir">
                </div>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary w-50">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary w-50">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
