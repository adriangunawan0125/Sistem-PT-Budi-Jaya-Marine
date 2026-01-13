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
    'name' => 'Admin Marine',
    'email' => 'adminmarine@gmail.com',
    'password' => Hash::make('marine123'),
    'role' => 'admin_marine',
]);

User::create([
    'name' => 'Admin Transport',
    'email' => 'admintransport@gmail.com',
    'password' => Hash::make('transport123'),
    'role' => 'admin_transport',
]);

    }
}
