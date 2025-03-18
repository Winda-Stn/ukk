<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->get();
        return view('crud.barang.index', compact('barang'));
    }
    
    
    
    public function create()
    {
        $kategori = Kategori::all(); // Ambil semua data kategori dari database
        return view('crud.barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required|string',
            'tanggal_kadaluarsa' => 'required|date',
            'tanggal_pembelian' => 'required|date',
            'harga_beli' => 'required|numeric',
            'stok' => 'required|numeric',
            'min_stok' => 'required|numeric',
            'id_kategori' => 'required'
        ]);

        // Hitung harga jual otomatis
        $harga_jual_1 = $request->harga_beli * 1.10; // Harga jual 1 (HPP + 10%)
        $harga_jual_2 = $request->harga_beli * 1.20; // Harga jual 2 (HPP + 20%)
        $harga_jual_3 = $request->harga_beli * 1.30; // Harga jual 3 (HPP + 30%)

        // Simpan ke database
        Barang::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'harga_beli' => $request->harga_beli,
            'harga_jual_1' => $harga_jual_1,
            'harga_jual_2' => $harga_jual_2,
            'harga_jual_3' => $harga_jual_3,
            'stok' => $request->stok,
            'min_stok' => $request->min_stok,
            'id_kategori' => $request->id_kategori,
        ]);

        return redirect()->route('crud.barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategori = Kategori::all();
        return view('crud.barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required|string',
            'nama_barang' => 'required|string|max:255',
            'tanggal_kadaluarsa' => 'required|date',
            'tanggal_pembelian' => 'required|date',
            'harga_beli' => 'required|numeric',
            'stok' => 'required|integer',
            'min_stok' => 'required|integer',
            'id_kategori' => 'required'
        ]);

        $barang = Barang::findOrFail($id);

        // Hitung ulang harga jual
        $harga_jual_1 = $request->harga_beli * 1.10;
        $harga_jual_2 = $request->harga_beli * 1.20;
        $harga_jual_3 = $request->harga_beli * 1.30;

        // Update data
        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'harga_beli' => $request->harga_beli,
            'harga_jual_1' => $harga_jual_1,
            'harga_jual_2' => $harga_jual_2,
            'harga_jual_3' => $harga_jual_3,
            'stok' => $request->stok,
            'min_stok' => $request->min_stok,
            'id_kategori' => $request->id_kategori,
        ]);

        return redirect()->route('crud.barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete(); // ✅ Soft Delete (tidak benar-benar dihapus dari database)
    
        return redirect()->route('crud.barang.index')->with('success', 'Barang berhasil dihapus (soft delete).');
    }
    
}
