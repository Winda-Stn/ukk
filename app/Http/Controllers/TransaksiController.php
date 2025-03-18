<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Menampilkan daftar transaksi.
     */
    public function index()
    {
        $transaksi = Transaksi::with(['kasir', 'pelanggan'])->latest()->get();
        return view('transaksi.index', compact('transaksi'));
    }

    /**
     * Menampilkan form transaksi baru.
     */
    public function create()
    {
        $pelanggan = User::where('role', 'pelanggan')->whereIn('tipe_pelanggan', [1, 2, 3])->get();
        $kasir = auth()->user(); // Kasir adalah user yang login
        $barang = Barang::all();

        return view('transaksi.create', compact('pelanggan', 'kasir', 'barang'));
    }

    /**
     * Menyimpan transaksi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:users,id',
            'barang' => 'required|array',
            'barang.*.id' => 'required|exists:barang,id',
            'barang.*.jumlah' => 'required|integer|min:1',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'poin_digunakan' => 'nullable|numeric|min:0',
            'uang_dibayar' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $kasir = auth()->user();
            $pelanggan = User::find($request->pelanggan_id);
            $tipe_pelanggan = $pelanggan ? $pelanggan->tipe_pelanggan : 'Non-Member';

            // Hitung total harga sebelum diskon dan PPN
            $total_pembelanjaan = 0;
            foreach ($request->barang as $item) {
                $barang = Barang::findOrFail($item['id']);
                if ($barang->stok < $item['jumlah']) {
                    return back()->withErrors(['msg' => "Stok {$barang->nama} tidak mencukupi!"]);
                }
                $total_pembelanjaan += $barang->harga * $item['jumlah'];
                $barang->decrement('stok', $item['jumlah']); // Kurangi stok barang
            }

            // Hitung diskon pelanggan berdasarkan tipe pelanggan
            $diskon_pelanggan = 0;
            if ($pelanggan) {
                if (in_array($tipe_pelanggan, [1, 2])) {
                    $diskon_pelanggan = $total_pembelanjaan * 0.05;
                } elseif ($tipe_pelanggan == 3) {
                    $diskon_pelanggan = $total_pembelanjaan * 0.10;
                }
            }

            // Hitung diskon berdasarkan threshold total belanja
            $diskon_threshold = 0;
            if ($total_pembelanjaan > 100000) {
                $diskon_threshold = $total_pembelanjaan * 0.10;
            } elseif ($total_pembelanjaan > 50000) {
                $diskon_threshold = $total_pembelanjaan * 0.05;
            }

            // Diskon dari input kasir
            $diskon_persen = $request->diskon_persen ?? 0;
            $diskon_nominal = ($diskon_persen / 100) * $total_pembelanjaan;

            // Total semua diskon
            $total_diskon = $diskon_pelanggan + $diskon_threshold + $diskon_nominal;

            // Hitung poin yang digunakan (maksimal sesuai saldo pelanggan)
            $poin_digunakan = min($request->poin_digunakan ?? 0, $pelanggan->poin ?? 0);
            if ($pelanggan) {
                $pelanggan->decrement('poin', $poin_digunakan);
            }

            // Hitung total setelah diskon dan poin
            $total_setelah_diskon = $total_pembelanjaan - $total_diskon - $poin_digunakan;
            $ppn = 0.12 * $total_setelah_diskon;
            $total_akhir = $total_setelah_diskon + $ppn;

            // Hitung kembalian
            $uang_dibayar = $request->uang_dibayar;
            $kembalian = max(0, $uang_dibayar - $total_akhir);

            // Simpan transaksi
            $transaksi = Transaksi::create([
                'user_id' => $kasir->id,
                'pelanggan_id' => $pelanggan->id ?? null,
                'tipe_pelanggan' => $tipe_pelanggan,
                'total_pembelanjaan' => $total_pembelanjaan,
                'diskon_persen' => $diskon_persen,
                'diskon_nominal' => $total_diskon,
                'poin_digunakan' => $poin_digunakan,
                'total_setelah_diskon' => $total_setelah_diskon,
                'ppn' => $ppn,
                'total_akhir' => $total_akhir,
                'uang_dibayar' => $uang_dibayar,
                'kembalian' => $kembalian,
            ]);

            // Tambahkan poin pelanggan (2% dari total setelah diskon)
            if ($pelanggan) {
                $poin_baru = 0.02 * $total_setelah_diskon;
                $pelanggan->increment('poin', $poin_baru);
            }
        });

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
    }
}
