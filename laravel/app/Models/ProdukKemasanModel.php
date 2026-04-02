<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukKemasanModel extends Model
{
    use HasFactory;
    protected $table = 'produk_kemasans';
    protected $fillable = ['produk_id', 'nama', 'konversi', 'harga_jual'];

    public function produk()
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id');
    }
}
