@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">Tambah Petugas</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            Form Tambah Petugas
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
                        <option value="kasir">Kasir</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Tambah Petugas</button>
            </form>
        </div>
    </div>
</div>
@endsection
