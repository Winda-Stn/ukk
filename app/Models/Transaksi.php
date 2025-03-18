<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualan';

    protected $fillable = [
        'pelanggan_id', 
        'user_id', 
        'total_pembelanjaan', 
        'diskon_persen', 
        'diskon_nominal', 
        'total_setelah_diskon', 
        'uang_dibayar', 
        'kembalian', 
        'poin_digunakan',
        'poin_didapat', 
    ];

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'pelanggan_id'); // Sesuaikan dengan nama kolom di controller
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id'); // Sesuaikan dengan nama kolom di controller
    }

    public function detail()
    {
        return $this->hasMany(TransaksiDetail::class, 'id_transaksi'); // Pastikan ini sesuai dengan relasi detail
    }
}