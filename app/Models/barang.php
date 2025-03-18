<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini

class Barang extends Model
{
    use HasFactory, SoftDeletes; // Tambahkan SoftDeletes agar bisa soft delete

    protected $table = 'barangs'; // Pastikan sesuai dengan nama tabel di database

    protected $fillable = [
        'id_kategori',
        'kode_barang',
        'nama_barang',
        'tanggal_pembelian',
        'harga_beli',
        'harga_jual_1',
        'harga_jual_2',
        'harga_jual_3',
        'stok',
        'min_stok'
    ];

    protected $dates = [
        'deleted_at',
        'tanggal_kadaluwarsa'
    ]; // Pastikan deleted_at dikenali sebagai tipe tanggal
    protected $casts = [
        'tanggal_kadaluarsa' => 'datetime', // Konversi otomatis ke Carbon
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori'); // Sesuaikan dengan foreign key
    }
}
