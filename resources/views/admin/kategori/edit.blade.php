@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Kategori</h1>
    <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
