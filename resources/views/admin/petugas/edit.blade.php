@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Data Petugas</h1>

    <form action="{{ route('admin.petugas.update', $petugas->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_petugas" class="form-label">Nama Petugas</label>
            <input type="text" class="form-control @error('nama_petugas') is-invalid @enderror" id="nama_petugas" name="nama_petugas" value="{{ old('nama_petugas', $petugas->nama_petugas) }}">
            @error('nama_petugas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $petugas->email) }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (Opsional)</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                {{-- <option value="petugas" {{ old('role', $petugas->role) == 'petugas' ? 'selected' : '' }}>Petugas</option> --}}
                <option value="kasir" {{ old('role', $petugas->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
