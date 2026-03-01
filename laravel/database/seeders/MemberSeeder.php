<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelMember;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        ModelMember::create([
            'kode_member' => 'MBR001',
            'nama_member' => 'Budi',
            'no_hp' => '08123456789',
            'poin' => 100
        ]);
    }
}
