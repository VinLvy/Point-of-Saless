@extends('layouts.kasir')

@section('content')
    <h1>Form Pembelian</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('kasir.pembelian.proses') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="pelanggan_id" class="form-label">Pilih Pelanggan</label>
            <select name="pelanggan_id" id="pelanggan_id" class="form-select" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach($pelanggan as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                @endforeach
            </select>
        </div>

        <div id="produk-wrapper">
            <div class="mb-3">
                <label for="produk_id" class="form-label">Pilih Produk</label>
                <select name="produk_id[]" class="form-select" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produk as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_produk }} - Rp{{ $p->harga }}</option>
                    @endforeach
                </select>
                <input type="number" name="jumlah[]" class="form-control mt-2" placeholder="Jumlah" min="1" required>
            </div>
        </div>

        <button type="button" class="btn btn-secondary" id="add-produk">Tambah Produk</button>

        <div class="mb-3 mt-3">
            <label for="total_bayar" class="form-label">Total Bayar</label>
            <input type="number" name="total_bayar" id="total_bayar" class="form-control" placeholder="Masukkan jumlah uang" required>
        </div>

        <button type="submit" class="btn btn-primary">Proses Transaksi</button>
    </form>

    @if (session('error'))
        <div class="alert alert-danger" style="border-left: 5px solid #dc3545; background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 10px; margin-top: 10px;">
            <strong>Peringatan!</strong> {{ session('error') }}
        </div>
    @endif

    <script>
        document.getElementById('add-produk').addEventListener('click', function () {
            const wrapper = document.getElementById('produk-wrapper');
            const newProduct = wrapper.children[0].cloneNode(true);
            wrapper.appendChild(newProduct);
        });
    </script>
@endsection
