<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukModel extends Model
{
    use HasFactory;
    protected $table = 'produks';
    protected $fillable = ['barcode', 'nama', 'harga', 'stok'];

    // Relasi ke kemasan
    public function kemasan()
    {
        return $this->hasMany(ProdukKemasanModel::class, 'produk_id');
    }
}
