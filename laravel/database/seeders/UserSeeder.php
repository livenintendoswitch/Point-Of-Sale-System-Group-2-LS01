<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        UserModel::create([
            'name' => 'Admin Utama',
            'email' => 'admin@pos.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        UserModel::create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@pos.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
        ]);
    }
}
