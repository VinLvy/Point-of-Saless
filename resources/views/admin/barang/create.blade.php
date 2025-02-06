@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Barang</h4>
            <a href="{{ route('admin.barang.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.barang.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Kedaluarsa</label>
                            <input type="date" name="tanggal_kedaluarsa" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Beli</label>
                            <input type="date" name="tanggal_pembelian" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Beli</label>
                            <input type="number" name="harga_beli" id="harga_beli" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Harga Jual 1 (+10%)</label>
                            <input type="number" name="harga_jual_1" id="harga_jual_1" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Jual 2 (+20%)</label>
                            <input type="number" name="harga_jual_2" id="harga_jual_2" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Jual 3 (+30%)</label>
                            <input type="number" name="harga_jual_3" id="harga_jual_3" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Minimal Stok</label>
                            <input type="number" name="minimal_stok" class="form-control" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3">
                    <i class="bi bi-save"></i> Simpan Barang
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Skrip untuk menghitung harga jual otomatis --}}
<script>
    document.getElementById('harga_beli').addEventListener('input', function () {
        let hargaBeli = parseFloat(this.value) || 0;
        document.getElementById('harga_jual_1').value = Math.round(hargaBeli * 1.1);
        document.getElementById('harga_jual_2').value = Math.round(hargaBeli * 1.2);
        document.getElementById('harga_jual_3').value = Math.round(hargaBeli * 1.3);
    });
</script>
@endsection
