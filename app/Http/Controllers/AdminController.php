<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        $incomeData = DB::table('detail_pembelian')
            ->selectRaw('DATE(created_at) as date, SUM(total_harga) as total_income')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('total_income', 'date')
            ->toArray();

        if (empty($incomeData)) {
            $incomeData = [];
        }

        $labels = array_keys($incomeData);
        $data = array_values($incomeData);

        return view('admin.dashboard', compact('labels', 'data'));
    }
}
