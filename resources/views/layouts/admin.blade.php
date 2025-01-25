<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Admin Dashboard</title>
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 222px;
            height: 100vh;
            background: #333;
            color: #fff;
            padding: 20px;
            position: fixed;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #555;
            padding-bottom: 10px;
        }
        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px 0;
            border-bottom: 1px solid #555;
        }
        .sidebar a:hover {
            background: #555;
        }
        .content {
            margin-left: 280px; /* Tambahkan lebih banyak margin agar tidak tertutup sidebar */
            padding: 20px;
            width: calc(100% - 280px); /* Pastikan lebar menyesuaikan */
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.petugas.index') }}">Pengguna</a>
        <a href="#">Laporan</a>
        <a href="#">Pengaturan</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        
    </div>

    <div class="content">
        @yield('content')
    </div>

</body>
</html>
