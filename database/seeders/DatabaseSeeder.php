<?php

namespace Database\Seeders;

use App\Models\Licenca;
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
            Licenca::class,
        ]);
    }
}
