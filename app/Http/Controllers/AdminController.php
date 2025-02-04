<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Tanggal awal dan akhir minggu ini
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        // Ambil data total pendapatan per hari dalam minggu ini
        $incomeData = DB::table('laporan_penjualan')
            ->join('detail_laporan_penjualan', 'laporan_penjualan.id', '=', 'detail_laporan_penjualan.laporan_penjualan_id')
            ->selectRaw('DATE(laporan_penjualan.tanggal_transaksi) as date, SUM(detail_laporan_penjualan.total_harga) as total_income')
            ->whereBetween('laporan_penjualan.tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total_income', 'date')
            ->toArray();

        // Jika tidak ada data, set array kosong
        if (empty($incomeData)) {
            $incomeData = [];
        }

        // Generate labels (tanggal) dan data (total pendapatan)
        $labels = array_keys($incomeData);
        $data = array_values($incomeData);

        return view('admin.dashboard', compact('labels', 'data'));
    }
}