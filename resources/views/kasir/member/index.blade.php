@extends('layouts.kasir')

@section('content')
<div class="container mt-4">
    <h1 class="mb-3">Data Pelanggan</h1>
    
    <a href="{{ route('kasir.member.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-lg"></i> Tambah Pelanggan
    </a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Tipe Pelanggan</th>
                        <th>Poin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($member as $p)
                    <tr>
                        <td>{{ $p->nama_pelanggan }}</td>
                        <td>{{ $p->email ?? '-' }}</td>
                        <td>{{ $p->no_hp }}</td>
                        <td>{{ $p->alamat ?? '-' }}</td>
                        <td><span class="badge bg-info text-dark">{{ $p->tipe_pelanggan }}</span></td>
                        <td><span class="badge bg-warning">{{ $p->poin_membership }}</span></td>
                        <td>
                            <a href="{{ route('kasir.member.edit', $p->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $p->id }})">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                            <form id="delete-form-{{ $p->id }}" action="{{ route('kasir.member.destroy', $p->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Apakah Anda yakin ingin menghapus pelanggan ini?")) {
            document.getElementById("delete-form-" + id).submit();
        }
    }
</script>
@endsection
