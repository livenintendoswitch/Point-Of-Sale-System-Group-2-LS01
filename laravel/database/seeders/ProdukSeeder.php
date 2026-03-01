<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelProduk;
use App\Models\ModelHargaProduk;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $produk = ModelProduk::create([
            'barcode' => '89900001',
            'nama_produk' => 'Indomie Goreng',
            'isi_per_slop' => 10,
            'isi_per_box' => 40,
            'stok_pcs' => 200
        ]);

        ModelHargaProduk::create([
            'produk_id' => $produk->id,
            'jenis' => 'satuan',
            'harga' => 3000
        ]);

        ModelHargaProduk::create([
            'produk_id' => $produk->id,
            'jenis' => 'slop',
            'harga' => 28000
        ]);

        ModelHargaProduk::create([
            'produk_id' => $produk->id,
            'jenis' => 'box',
            'harga' => 110000
        ]);
    }
}
