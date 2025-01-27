<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPembelian;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subWeek()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

        $laporan = DetailPembelian::whereBetween('created_at', [$startDate, $endDate])->get();
        return view('admin.laporan.index', compact('laporan', 'startDate', 'endDate'));
    }

    public function show($id)
    {
        $detail = DetailPembelian::findOrFail($id);
        return view('admin.laporan.detail', compact('detail'));
    }
}
