@extends('layouts.admin')

@section('content')
<div class="container mt-4">
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
            <h4 class="mb-0 bi bi-pencil-square"> Edit Data Petugas</h4>
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
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Masukkan ulang password">
                    <small id="password-match" class="text-danger d-none">Password tidak cocok!</small>
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
                    <button type="submit" id="submit-button" class="btn btn-primary w-50">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');
        const passwordMatchText = document.getElementById('password-match');
        const submitButton = document.getElementById('submit-button');

        function checkPasswordMatch() {
            if (passwordField.value !== confirmPasswordField.value) {
                passwordMatchText.classList.remove('d-none');
                submitButton.disabled = true;
            } else {
                passwordMatchText.classList.add('d-none');
                submitButton.disabled = false;
            }

            // Jika password kosong atau tidak diubah, enable tombol simpan perubahan
            if (passwordField.value === '' && confirmPasswordField.value === '') {
                submitButton.disabled = false;
            }
        }

        passwordField.addEventListener('input', checkPasswordMatch);
        confirmPasswordField.addEventListener('input', checkPasswordMatch);
    });
</script>
@endsection