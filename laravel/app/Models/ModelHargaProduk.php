<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelHargaProduk extends Model
{
    protected $table = 'harga_produk';

    protected $fillable = [
        'produk_id',
        'jenis',
        'harga'
    ];

    public function produk()
    {
        return $this->belongsTo(ModelProduk::class);
    }
}
