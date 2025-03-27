<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualan';

    protected $fillable = [
        'user_id',
        'pelanggan_id',
        'tipe_pelanggan',
        'total_pembelanjaan',
        'diskon_persen',
        'diskon_nominal',
        'poin_digunakan',
        'total_setelah_diskon',
        'ppn',
        'total_akhir',
        'uang_dibayar',
        'kembalian',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'pelanggan_id'); // Sesuaikan dengan nama kolom di controller
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id'); // Sesuaikan dengan nama kolom di controller
    }

    
// Di model Transaksi
public function detail()
{
    return $this->hasMany(TransaksiDetail::class);
}
}