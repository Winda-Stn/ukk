<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdKategoriToBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Cek apakah kolom 'id_kategori' sudah ada
        if (!Schema::hasColumn('barangs', 'id_kategori')) {
            Schema::table('barangs', function (Blueprint $table) {
                $table->unsignedBigInteger('id_kategori')->after('id');
                $table->foreign('id_kategori')->references('id')->on('kategoris')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Hapus foreign key dan kolom 'id_kategori'
            $table->dropForeign(['id_kategori']);
            $table->dropColumn('id_kategori');
        });
    }
}