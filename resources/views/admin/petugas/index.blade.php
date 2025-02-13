@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Manajemen Petugas</h1>
    
    <div class="mb-3">
        <a href="{{ route('admin.petugas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Petugas
        </a>
    </div>

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
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $p->id }}, '{{ $p->nama_petugas }}')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
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

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus <strong id="namaPetugas"></strong>?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, nama) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = '{{ route("admin.petugas.destroy", "") }}/' + id;
        document.getElementById('namaPetugas').textContent = nama;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endsection
