<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProdukModel;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $produks = [
            ['barcode' => '8991001101111', 'nama' => 'Indomie Goreng', 'harga' => 3500, 'stok' => 100],
            ['barcode' => '8991002102222', 'nama' => 'Aqua 600ml', 'harga' => 3000, 'stok' => 50],
            ['barcode' => '8991003103333', 'nama' => 'Pocari Sweat', 'harga' => 8000, 'stok' => 30],
            ['barcode' => '8991004104444', 'nama' => 'Roti Tawar', 'harga' => 12000, 'stok' => 20],
        ];
        foreach ($produks as $p) {
            ProdukModel::create($p);
        }
    }
}