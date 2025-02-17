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
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    <small class="text-muted">
                        <ul class="mt-2" id="password-requirements">
                            <li class="text-danger">Minimal 8 karakter</li>
                            <li class="text-danger">Mengandung huruf besar</li>
                            <li class="text-danger">Mengandung huruf kecil</li>
                            <li class="text-danger">Mengandung angka</li>
                        </ul>
                    </small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Masukkan ulang password" required>
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
                    <button type="submit" id="submit-button" class="btn btn-primary w-50" disabled>
                        <i class="bi bi-plus-circle"></i> Tambah Petugas
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
        const submitButton = document.getElementById('submit-button');
        const passwordMatchText = document.getElementById('password-match');
        const passwordRequirements = document.querySelectorAll('#password-requirements li');

        function validatePassword() {
            const password = passwordField.value;
            let isValid = true;

            const rules = [
                { regex: /.{8,}/, element: passwordRequirements[0] }, // Minimal 8 karakter
                { regex: /[A-Z]/, element: passwordRequirements[1] }, // Huruf besar
                { regex: /[a-z]/, element: passwordRequirements[2] }, // Huruf kecil
                { regex: /\d/, element: passwordRequirements[3] }, // Angka
            ];

            rules.forEach(rule => {
                if (rule.regex.test(password)) {
                    rule.element.classList.remove('text-danger');
                    rule.element.classList.add('text-success');
                } else {
                    rule.element.classList.remove('text-success');
                    rule.element.classList.add('text-danger');
                    isValid = false;
                }
            });

            return isValid;
        }

        function checkPasswordMatch() {
            if (passwordField.value !== confirmPasswordField.value) {
                passwordMatchText.classList.remove('d-none');
                submitButton.disabled = true;
            } else {
                passwordMatchText.classList.add('d-none');
                submitButton.disabled = !validatePassword();
            }
        }

        passwordField.addEventListener('input', function () {
            validatePassword();
            checkPasswordMatch();
        });

        confirmPasswordField.addEventListener('input', checkPasswordMatch);
    });
</script>

@endsection
