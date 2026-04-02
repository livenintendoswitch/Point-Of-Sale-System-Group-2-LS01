<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MemberModel;

class MemberSeeder extends Seeder
{
    public function run()
    {
        MemberModel::create([
            'kode_member' => 'MBR001',
            'nama' => 'Budi Santoso',
            'telepon' => '08123456789',
            'poin' => 100,
        ]);
        MemberModel::create([
            'kode_member' => 'MBR002',
            'nama' => 'Siti Aminah',
            'telepon' => '08129876543',
            'poin' => 50,
        ]);
    }
}
