<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelProduk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'barcode',
        'nama_produk',
        'isi_per_slop',
        'isi_per_box',
        'stok_pcs'
    ];

    public function harga()
    {
        return $this->hasMany(ModelHargaProduk::class);
    }

    public function detailPenjualan()
    {
        return $this->hasMany(ModelDetailPenjualan::class);
    }
}
