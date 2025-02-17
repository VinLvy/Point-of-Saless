<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <!-- jQuery (diperlukan oleh Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <title>Admin Dashboard</title>
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }
        .sidebar {
            width: 244px;
            height: 100vh;
            background: #222;
            color: #fff;
            padding-top: 20px;
            position: fixed;
            transition: all 0.3s;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
            font-size: 20px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            color: #ddd;
            text-decoration: none;
            padding: 12px 20px;
            font-size: 16px;
            transition: background 0.3s;
        }
        .sidebar a i {
            margin-right: 10px;
            font-size: 18px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #007bff;
            color: #fff;
        }
        .logout {
            position: absolute;
            bottom: 20px;
            width: 100%;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            box-sizing: border-box;
        }
        /* Responsif: Sidebar bisa ditutup di mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            .content {
                margin-left: 0;
                width: 100%;
            }
            .sidebar.open {
                width: 250px;
            }
        }
    </style>
</head>
<body>

    {{-- Notifikasi Error dengan SweetAlert2 --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `
                    <ul style='text-align: left;'>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
            });
        </script>
    @endif

    <div class="sidebar">
        <h2><i class="bi bi-speedometer2"></i> Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i> Dashboard</a>
        <a href="{{ route('admin.transaksi.index') }}"><i class="bi bi-basket2-fill"></i> Transaksi</a>
        <a href="{{ route('admin.petugas.index') }}"><i class="bi bi-person-badge"></i> Petugas</a>
        <a href="{{ route('admin.pelanggan.index') }}"><i class="bi bi-people"></i> Pelanggan</a>
        <a href="{{ route('admin.laporan.index') }}"><i class="bi bi-file-earmark-text"></i> Laporan Transaksi</a>
        <a href="{{ route('admin.terjual.index') }}"><i class="bi bi-file-earmark-spreadsheet"></i> Laporan Penjualan</a>
        <a href="{{ route('admin.kategori.index') }}"><i class="bi bi bi-journal-check"></i> Kategori Barang</a>
        <a href="{{ route('admin.barang.index') }}"><i class="bi bi-box-seam-fill"></i> Barang</a>
        <a href="{{ route('admin.stok.index') }}"><i class="bi bi-inboxes"></i> Stok</a>
        <a href="{{ route('admin.logs.index') }}"><i class="bi bi-clock-history"></i> Riwayat Aktivitas</a>
        <a href="#" class="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <div class="content">
        @yield('content')
    </div>

</body>
</html>
