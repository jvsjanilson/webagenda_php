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
        $this->call([
            EmpresaSeeder::class,
            UserSeeder::class,
            UserEmpresaSeeder::class,
            ConfigSeeder::class,
            LicencaSeed::class,
        ]);
    }
}
