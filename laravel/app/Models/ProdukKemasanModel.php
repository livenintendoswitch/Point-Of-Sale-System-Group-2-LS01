<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukKemasanModel extends Model
{
    use HasFactory;
    protected $table = 'produk_kemasans';
    protected $fillable = ['produk_id', 'nama', 'satuan', 'konversi', 'harga_beli', 'harga_jual'];

    public function produk()
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id');
    }

    public function hitungTotalPcsPerUnit()
    {
        $semuaKemasan = self::where('produk_id', $this->produk_id)->get();
        
        $besar = $semuaKemasan->where('nama', 'Besar')->first();
        $sedang = $semuaKemasan->where('nama', 'Sedang')->first();
        
        if ($this->nama == 'Besar') {
            // Rumus: Konversi Besar (Bal ke Slop) * Konversi Sedang (Slop ke Pack)
            return ($besar->konversi ?? 1) * ($sedang->konversi ?? 1);
        } 
        
        if ($this->nama == 'Sedang') {
            // Rumus: Konversi Sedang (Slop ke Pack)
            return $sedang->konversi ?? 1;
        }

        return 1; // Jika kecil (Pack)
    }
}