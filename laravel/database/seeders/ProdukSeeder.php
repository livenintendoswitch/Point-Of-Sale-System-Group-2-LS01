<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProdukModel;
use App\Models\ProdukKemasanModel;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $produks = [
            ['barcode' => '8991001101111', 'nama' => 'Rokok Surya 12', 'stok' => 1000], // Stok dalam satuan terkecil (Pack)
        ];

        foreach ($produks as $p) {
            $produk = ProdukModel::create([
                'barcode' => $p['barcode'],
                'nama'    => $p['nama'],
                'stok'    => $p['stok'],
                // 'harga' => 0, // Aktifkan jika migrasi tabel produks masih ada kolom harga
            ]);

            // 1. KEMASAN BESAR (Bal) -> Berisi 20 Slop
            ProdukKemasanModel::create([
                'produk_id'  => $produk->id,
                'nama'       => 'Besar',
                'satuan'     => 'Bal',
                'konversi'   => 20, // 1 Bal = 20 Slop
                'harga_beli' => 450000, 
                'harga_jual' => 500000,
            ]);

            // 2. KEMASAN SEDANG (Slop) -> Berisi 10 Pack
            ProdukKemasanModel::create([
                'produk_id'  => $produk->id,
                'nama'       => 'Sedang',
                'satuan'     => 'Slop',
                'konversi'   => 10, // 1 Slop = 10 Pack
                'harga_beli' => 220000, 
                'harga_jual' => 240000,
            ]);

            // 3. KEMASAN KECIL (Pack) -> Satuan Dasar
            ProdukKemasanModel::create([
                'produk_id'  => $produk->id,
                'nama'       => 'Kecil',
                'satuan'     => 'Pack',
                'konversi'   => 1,
                'harga_beli' => 20000,  
                'harga_jual' => 22000,
            ]);
        }
    }
}