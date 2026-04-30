<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualanModel extends Model
{
    use HasFactory;
    protected $table = 'detail_penjualans';
    // DetailPenjualanModel.php
    protected $fillable = [
        'penjualan_id', 
        'produk_id', 
        'produk_kemasan_id', 
        'jumlah', 
        'harga_satuan', 
        'harga_beli', 
        'subtotal', 
        'konversi_saat_itu' 
    ];

    public function kemasan()
    {
        return $this->belongsTo(ProdukKemasanModel::class, 'produk_kemasan_id');
    }

    public function produk()
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id');
    }
}
