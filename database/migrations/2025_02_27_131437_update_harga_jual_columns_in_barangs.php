<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn('harga_jual'); // Hapus kolom lama
            $table->integer('harga_jual_1')->after('harga_beli')->default(0);
            $table->integer('harga_jual_2')->after('harga_jual_1')->default(0);
            $table->integer('harga_jual_3')->after('harga_jual_2')->default(0);
        });
    }

    public function down()
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['harga_jual_1', 'harga_jual_2', 'harga_jual_3']);
            $table->integer('harga_jual')->after('harga_beli')->default(0); // Mengembalikan kolom lama jika rollback
        });
    }
};
