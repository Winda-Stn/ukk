<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->date('tanggal_kadaluarsa');
            $table->date('tanggal_pembelian');
            $table->decimal('harga_beli', 10, 2);
            $table->decimal('harga_jual', 10, 2)->nullable();
            $table->integer('stok');
            $table->integer('min_stok');
            $table->timestamps();
        });
    }
    


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
