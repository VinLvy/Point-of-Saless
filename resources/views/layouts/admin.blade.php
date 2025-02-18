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
            width: 230px;
            height: 100vh;
            background: #34495e;
            color: #fff;
            padding-top: 20px;
            position: fixed;
            transition: all 0.3s;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            font-size: 18px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 14px;
            transition: background 0.3s;
        }
        .sidebar a i {
            margin-right: 8px;
            font-size: 20px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #2980b9;
            color: #fff;
        }
        .dropdown-menu {
            background: #2c3e50;
        }
        .dropdown-menu a {
            color: #ecf0f1;
            font-size: 13px;
        }
        .dropdown-menu a:hover {
            background: #2980b9;
        }
        .logout {
            position: absolute;
            bottom: 20px;
            width: 100%;
        }
        .content {
            margin-left: 240px;
            padding: 20px;
            width: calc(100% - 240px);
            box-sizing: border-box;
        }
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
                width: 230px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="bi bi-speedometer2"></i> Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}" class="{{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        <a href="{{ route('admin.transaksi.index') }}" class="{{ Request::routeIs('admin.transaksi.index') ? 'active' : '' }}">
            <i class="bi bi-basket2-fill"></i> Transaksi
        </a>
        <a href="{{ route('admin.petugas.index') }}" class="{{ Request::routeIs('admin.petugas.index') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Petugas
        </a>
        <a href="{{ route('admin.pelanggan.index') }}" class="{{ Request::routeIs('admin.pelanggan.index') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Pelanggan
        </a>
        
        <div class="dropdown">
            <a href="#" class="dropdown-toggle {{ Request::routeIs('admin.laporan.index', 'admin.terjual.index') ? 'active' : '' }}" data-bs-toggle="dropdown">
                <i class="bi bi-journal-check"></i> Laporan
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item {{ Request::routeIs('admin.laporan.index') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">Laporan Transaksi</a>
                <a class="dropdown-item {{ Request::routeIs('admin.terjual.index') ? 'active' : '' }}" href="{{ route('admin.terjual.index') }}">Laporan Penjualan</a>
            </div>
        </div>
        
        <div class="dropdown">
            <a href="#" class="dropdown-toggle {{ Request::routeIs('admin.kategori.index', 'admin.barang.index', 'admin.stok.index') ? 'active' : '' }}" data-bs-toggle="dropdown">
                <i class="bi bi-box-seam-fill"></i> Barang
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item {{ Request::routeIs('admin.kategori.index') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">Kategori Barang</a>
                <a class="dropdown-item {{ Request::routeIs('admin.barang.index') ? 'active' : '' }}" href="{{ route('admin.barang.index') }}">Data Barang</a>
                <a class="dropdown-item {{ Request::routeIs('admin.stok.index') ? 'active' : '' }}" href="{{ route('admin.stok.index') }}">Stok Barang</a>
            </div>
        </div>
        
        <a href="{{ route('admin.logs.index') }}" class="{{ Request::routeIs('admin.logs.index') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Riwayat Aktivitas
        </a>
        
        <a href="#" class="logout bg-danger text-white" onclick="confirmLogout(event)">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>    
    
    <div class="content">
        @yield('content')
    </div>
    
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</body>
</html>
