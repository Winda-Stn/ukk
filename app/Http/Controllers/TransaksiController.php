<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Barang;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TransaksiController extends Controller
{
    /**
     * Menampilkan daftar transaksi.
     */
    public function index(): View
    {
        $transaksi = Transaksi::with(['kasir', 'pelanggan', 'detail.barang'])
            ->latest()
            ->get();
            
        return view('transaksi.index', compact('transaksi'));
    }

    /**
     * Menampilkan form transaksi baru.
     */
    public function create(): View
    {
        $pelanggan = User::where('role', 'pelanggan')
            ->whereIn('tipe_pelanggan', [1, 2, 3])
            ->get();
            
        $barang = Barang::select([
            'id', 
            'nama_barang', 
            'harga_jual_1', 
            'harga_jual_2', 
            'harga_jual_3', 
            'stok'
        ])->where('stok', '>', 0)->get(); // Hanya tampilkan barang dengan stok > 0
        
        return view('transaksi.create', [
            'pelanggan' => $pelanggan,
            'barang' => $barang,
            'kasir' => auth()->user()
        ]);
    }

    /**
     * Menyimpan transaksi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pelanggan_id' => 'nullable|exists:users,id',
            'total_pembelanjaan' => 'required|numeric|min:0',
            'diskon_nominal' => 'required|numeric|min:0',
            'ppn' => 'required|numeric|min:0',
            'total_akhir' => 'required|numeric|min:0',
            'poin_digunakan' => 'required|numeric|min:0',
            'uang_dibayar' => 'required|numeric|min:'.$request->total_akhir,
            'kembalian' => 'required|numeric|min:0',
            'barang' => 'required|array|min:1',
            'barang.*.id' => 'required|exists:barangs,id',
            'barang.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Validasi tambahan sebelum transaksi
            $this->validateTransaction($request);

            // Get customer type
            $tipePelanggan = 1; // Default type 1 jika tidak ada pelanggan
            if ($request->pelanggan_id) {
                $pelanggan = User::findOrFail($request->pelanggan_id);
                $tipePelanggan = $pelanggan->tipe_pelanggan ?? 1;
            }

            // Save main transaction
            $transaksi = Transaksi::create([
                'user_id' => $request->user_id,
                'pelanggan_id' => $request->pelanggan_id,
                'tipe_pelanggan' => $tipePelanggan,
                'total_pembelanjaan' => $request->total_pembelanjaan,
                'diskon_nominal' => $request->diskon_nominal,
                'ppn' => $request->ppn,
                'total_akhir' => $request->total_akhir,
                'poin_digunakan' => $request->poin_digunakan,
                'uang_dibayar' => $request->uang_dibayar,
                'kembalian' => $request->kembalian,
            ]);

            // Save transaction details
            foreach ($request->barang as $item) {
                $barang = Barang::findOrFail($item['id']);
                $hargaSatuan = $this->getHargaByTipe($barang, $tipePelanggan);
                
                $transaksi->detail()->create([
                    'barang_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $hargaSatuan * $item['jumlah'],
                ]);
                
                // Reduce stock
                $barang->decrement('stok', $item['jumlah']);
            }

            // Deduct membership points if used
            if ($request->pelanggan_id && $request->poin_digunakan > 0) {
                User::where('id', $request->pelanggan_id)
                    ->decrement('membership_points', $request->poin_digunakan);
            }

            DB::commit();

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaksi gagal: '.$e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
                'user' => auth()->id()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Transaksi gagal: '.$e->getMessage());
        }
    }

    /**
     * Validasi tambahan untuk transaksi
     */
    private function validateTransaction(Request $request): void
    {
        // Cek stok barang
        foreach ($request->barang as $item) {
            $barang = Barang::find($item['id']);
            if (!$barang) {
                throw new \Exception("Barang dengan ID {$item['id']} tidak ditemukan");
            }
            
            if ($barang->stok < $item['jumlah']) {
                throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi");
            }
        }

        // Cek poin membership jika digunakan
        if ($request->pelanggan_id && $request->poin_digunakan > 0) {
            $pelanggan = User::find($request->pelanggan_id);
            if (!$pelanggan) {
                throw new \Exception("Pelanggan tidak ditemukan");
            }
            
            if ($pelanggan->membership_points < $request->poin_digunakan) {
                throw new \Exception("Poin membership tidak mencukupi");
            }
        }
    }

    /**
     * Get price based on customer type
     */
    private function getHargaByTipe(Barang $barang, int $tipePelanggan): float
    {
        $harga = match($tipePelanggan) {
            1 => $barang->harga_jual_1,
            2 => $barang->harga_jual_2,
            3 => $barang->harga_jual_3,
            default => $barang->harga_jual_1,
        };

        if ($harga <= 0) {
            throw new \Exception("Harga barang {$barang->nama_barang} untuk tipe pelanggan {$tipePelanggan} tidak valid");
        }

        return $harga;
    }
}