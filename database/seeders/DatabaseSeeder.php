<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Solo llamamos al que sí tiene código para evitar errores
        $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}