<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukModel extends Model
{
    use HasFactory;
    protected $table = 'produks';
    protected $fillable = ['barcode', 'nama', 'harga', 'stok',
        'satuan_besar', 'konversi_besar', 'stok_besar', 'harga_beli_besar', 'harga_jual_besar',
        'satuan_sedang', 'konversi_sedang', 'stok_sedang', 'harga_beli_sedang', 'harga_jual_sedang',
        'satuan_kecil', 'konversi_kecil', 'stok_kecil', 'harga_beli_kecil', 'harga_jual_kecil'];

    // Relasi ke kemasan
    public function kemasan()
    {
        return $this->hasMany(ProdukKemasanModel::class, 'produk_id');
    }
}
