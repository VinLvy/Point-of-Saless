@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Barang</h4>
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

            <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" value="{{ $barang->nama_barang }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori_id" class="form-select select2" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Satuan</label>
                            <input type="text" name="satuan" class="form-control" value="{{ $barang->satuan }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Minimal Stok</label>
                            <input type="number" name="minimal_stok" class="form-control" value="{{ $barang->minimal_stok }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Harga Beli</label>
                            <input type="number" name="harga_beli" id="harga_beli" class="form-control" value="{{ $barang->harga_beli }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Jual 1 (+10%)</label>
                            <input type="number" name="harga_jual_1" id="harga_jual_1" class="form-control" value="{{ $barang->harga_jual_1 }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Jual 2 (+20%)</label>
                            <input type="number" name="harga_jual_2" id="harga_jual_2" class="form-control" value="{{ $barang->harga_jual_2 }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Jual 3 (+30%)</label>
                            <input type="number" name="harga_jual_3" id="harga_jual_3" class="form-control" value="{{ $barang->harga_jual_3 }}" readonly>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 mt-3">
                    <i class="bi bi-save"></i> Simpan Perubahan
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

{{-- Skrip untuk select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih Kategori",
            allowClear: true
        });
    });
</script>
@endsection
