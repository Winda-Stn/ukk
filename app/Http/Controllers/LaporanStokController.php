<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Maatwebsite\Excel\Facades\Excel;


class LaporanStokController extends Controller
{
    /**
     * Menampilkan halaman laporan stok.
     */
    public function index(Request $request)
    {
        $laporan = Barang::with('kategori')->get();

        // Hitung total item dan total nilai stok
        $total_item = $laporan->sum('jumlah_stok');
        $total_nilai = $laporan->sum(fn($item) => $item->jumlah_stok * $item->harga_beli);
        $items_warning = $laporan->where('jumlah_stok', '<=', 'minimal_stok')->count();

        return view('laporann.index', compact('laporan', 'total_item', 'total_nilai', 'items_warning'));
    }

    /**
     * Ekspor laporan stok ke Excel.
     */
    public function export()
    {
        return Excel::download(new LaporanStokExport, 'laporann_stok.xlsx');
    }
}
