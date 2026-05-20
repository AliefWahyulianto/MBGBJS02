<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Dapur MBG',
            'email' => 'admin@mbg.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Manager Operasional',
            'email' => 'manager@mbg.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@mbg.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Driver Kirim',
            'email' => 'driver@mbg.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
        ]);
    }
}