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

    <title>Kasir Dashboard</title>
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
        <h2><i class="bi bi-speedometer2"></i> Kasir Panel</h2>
        <a href="{{ route('kasir.dashboard') }}" class="{{ Request::routeIs('kasir.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door"></i> Dashboard</a>

        <a href="{{ route('kasir.pembelian.index') }}" class="{{ Request::routeIs('kasir.pembelian.index') ? 'active' : '' }}"><i class="bi bi-basket2-fill"></i> Transaksi</a>

        <a href="{{ route('kasir.member.index') }}" class="{{ Request::routeIs('kasir.member.index') ? 'active' : '' }}"><i class="bi bi-people"></i> Pelanggan</a>
        
        <a href="{{ route('kasir.riwayat.index') }}" class="{{ Request::routeIs('kasir.riwayat.index') ? 'active' : '' }}"><i class="bi bi-file-earmark-text"></i> Riwayat Transaksi</a>
        
        <a href="{{ route('kasir.barang.index') }}" class="{{ Request::routeIs('kasir.barang.index') ? 'active' : '' }}"><i class="bi bi-box-seam-fill"></i> Barang</a>
        
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
