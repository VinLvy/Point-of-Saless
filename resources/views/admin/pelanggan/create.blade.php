@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 bi bi-plus-circle"> Tambah Data Pelanggan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pelanggan.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" 
                        class="form-control @error('nama_pelanggan') is-invalid @enderror"
                        value="{{ old('nama_pelanggan') }}" required>
                    @error('nama_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" 
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" 
                        class="form-control @error('no_hp') is-invalid @enderror" 
                        value="{{ old('no_hp') }}" required>
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea name="alamat" id="alamat" 
                        class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="poin_membership" class="form-label">Poin Membership</label>
                    <input type="number" name="poin_membership" id="poin_membership" 
                        class="form-control @error('poin_membership') is-invalid @enderror" value="0" min="0" disabled>
                    @error('poin_membership')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tipe_pelanggan" class="form-label">Tipe Pelanggan</label>
                    <select name="tipe_pelanggan" id="tipe_pelanggan" 
                        class="form-control @error('tipe_pelanggan') is-invalid @enderror" required>
                            {{-- <option value="tipe 1" {{ old('tipe_pelanggan') == 'tipe 1' ? 'selected' : '' }}>Tipe 1</option> --}}
                            <option value="tipe 2" {{ old('tipe_pelanggan') == 'tipe 2' ? 'selected' : '' }}>Tipe 2</option>
                    </select>
                    @error('tipe_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
