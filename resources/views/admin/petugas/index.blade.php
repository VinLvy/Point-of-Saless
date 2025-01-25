@extends('layouts.admin')

@section('content')
<h1>Manajemen Petugas</h1>

<!-- Form Tambah Petugas -->
<form action="{{ route('admin.petugas.store') }}" method="POST">
    @csrf
    <div>
        <label>Nama Petugas:</label>
        <input type="text" name="nama_petugas" required>
    </div>
    <div>
        <label>Email:</label>
        <input type="email" name="email" required>
    </div>
    <div>
        <label>Password:</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <label>Role:</label>
        <select name="role">
            <option value="petugas">Petugas</option>
            <option value="kasir">Kasir</option>
        </select>
    </div>
    <button type="submit">Tambah Petugas</button>
</form>

<!-- Tabel Petugas -->
<table border="1" cellspacing="0" cellpadding="10">
    <thead>
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
                <td>{{ ucfirst($p->role) }}</td>
                <td>
                    <form action="{{ route('admin.petugas.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

@endsection
