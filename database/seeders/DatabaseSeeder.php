<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Buat atau perbarui Admin
        User::updateOrCreate(
            ['email' => 'wind@gmail.com'], // Jika email sudah ada, update data
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Buat atau perbarui Kasir
        User::updateOrCreate(
            ['email' => 'kasir@gmail.com'], // Jika email sudah ada, update data
            [
                'name' => 'Kasir',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
            ]
        );
    }
}
