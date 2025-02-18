<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 10px; }
        .text-end { text-align: right; } /* Untuk Harga */
    </style>
</head>
<body>
    <div class="title">Laporan Data Barang</div>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Harga Beli</th>
                <th>Harga Jual 1</th>
                <th>Harga Jual 2</th>
                <th>Harga Jual 3</th>
                <th>Minimal Stok</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $index => $item)
                @php
                    $totalStok = $item->stok->sum('jumlah_stok');
                @endphp
                <tr>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kategori->nama_kategori }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td class="text-end">Rp{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-end">Rp{{ number_format($item->harga_jual_1, 0, ',', '.') }}</td>
                    <td class="text-end">Rp{{ number_format($item->harga_jual_2, 0, ',', '.') }}</td>
                    <td class="text-end">Rp{{ number_format($item->harga_jual_3, 0, ',', '.') }}</td>
                    <td>{{ $item->minimal_stok }}</td>
                    <td class="{{ $totalStok <= $item->minimal_stok ? 'text-danger fw-bold' : '' }}">
                        {{ $totalStok }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
