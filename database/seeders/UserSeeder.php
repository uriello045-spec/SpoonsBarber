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
            'name' => 'Uriel SuperAdmin',
            'email' => 'uriel.lo.045@gmail.com', // El que quieras usar
            'password' => Hash::make('190060260-6'), // La que tú elijas
            'role' => 'barbero',
            'is_superadmin' => true, // O 'superadmin' según tengas tus roles
        ]);
    }
}