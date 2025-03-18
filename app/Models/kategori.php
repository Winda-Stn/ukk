<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $fillable = ['kode_kategori', 'nama_kategori'];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_kategori'); // Sesuaikan dengan foreign key
    }
}
