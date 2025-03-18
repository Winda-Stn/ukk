<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['kasir', 'pelanggan']);

        // Filter berdasarkan tanggal jika tersedia
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        }

        $laporan = $query->get();

        $total_penjualan = $laporan->sum('total_pembelanjaan');
        $total_diskon = $laporan->sum('diskon_nominal');
        $total_poin = $laporan->sum('poin_digunakan');
        $total_akhir = $laporan->sum('total_akhir');

        return view('laporan.index', compact('laporan', 'total_penjualan', 'total_diskon', 'total_poin', 'total_akhir'));
    }

    public function export(Request $request)
    {
        return Excel::download(new LaporanExport($request->start_date, $request->end_date), 'laporan_index.xlsx');
    }
}
