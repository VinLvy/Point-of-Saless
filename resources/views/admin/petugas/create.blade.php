@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    {{-- <h1 class="my-4 text-center">Tambah Petugas</h1> --}}

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
            <h4 class="mb-0 bi bi-plus-circle"> Tambah Data Petugas</h4>
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
                    <input type="text" class="form-control" value="Kasir" disabled>
                    <input type="hidden" name="role" value="kasir">
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary w-50">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary w-50">
                        <i class="bi bi-plus-circle"></i> Tambah Petugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
