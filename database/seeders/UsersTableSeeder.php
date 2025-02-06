<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Buat admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'), // Gunakan Hash::make()
            'role' => 'admin',
        ]);

        // Buat user biasa
        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('user'), // Gunakan Hash::make()
            'role' => 'user',
        ]);
    }
}