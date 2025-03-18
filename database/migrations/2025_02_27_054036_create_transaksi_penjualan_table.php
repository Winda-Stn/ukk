<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('transaksi_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Nama kasir
            $table->foreignId('pelanggan_id')->nullable()->constrained('users')->onDelete('set null'); // Nama pelanggan
            $table->string('tipe_pelanggan'); // Tipe pelanggan (member/non-member)
            $table->decimal('total_pembelanjaan', 15, 2); // Total harga sebelum diskon dan PPN
            $table->decimal('diskon_persen', 5, 2)->default(0); // Diskon dalam persen
            $table->decimal('diskon_nominal', 15, 2)->default(0); // Diskon dalam nominal rupiah
            $table->decimal('poin_digunakan', 15, 2)->default(0); // Poin yang digunakan
            $table->decimal('total_setelah_diskon', 15, 2); // Total setelah diskon dan poin
            $table->decimal('ppn', 15, 2); // PPN 12%
            $table->decimal('total_akhir', 15, 2); // Total akhir setelah PPN
            $table->decimal('uang_dibayar', 15, 2); // Uang yang dibayarkan pelanggan
            $table->decimal('kembalian', 15, 2); // Kembalian otomatis dihitung
            $table->timestamp('tanggal_transaksi')->useCurrent(); // Tanggal transaksi otomatis
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualan');
    }
};