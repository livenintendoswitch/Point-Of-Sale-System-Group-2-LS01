<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelDetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'jenis',
        'qty',
        'harga',
        'subtotal'
    ];

    public function penjualan()
    {
        return $this->belongsTo(ModelPenjualan::class);
    }

    public function produk()
    {
        return $this->belongsTo(ModelProduk::class);
    }
}
