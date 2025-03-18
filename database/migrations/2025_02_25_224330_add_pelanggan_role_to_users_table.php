<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kasir', 'pelanggan'])->default('kasir')->change();
            $table->enum('tipe_pelanggan', ['tipe_1', 'tipe_2', 'tipe_3'])->nullable()->after('role');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kasir'])->default('kasir')->change();
            $table->dropColumn('tipe_pelanggan');
        });
    }
};
