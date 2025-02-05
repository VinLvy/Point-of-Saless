@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Tambah Pelanggan</h1>
    <form action="{{ route('admin.pelanggan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" name="no_hp" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Poin Membership</label>
            <input type="number" name="poin_membership" class="form-control" value="0" min="0">
        </div>
        <div class="mb-3">
            <label class="form-label">Tipe Pelanggan</label>
            <select name="tipe_pelanggan" class="form-control" required>
                <option value="tipe 1">Tipe 1</option>
                <option value="tipe 2">Tipe 2</option>
                {{-- <option value="tipe 3">Tipe 3</option> --}}
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
