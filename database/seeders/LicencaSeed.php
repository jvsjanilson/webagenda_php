<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LicencaSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::now();
        $OneYear = $today->addYear();

        DB::table('licencas')->insert([
            ['validade' => $OneYear->toDateString()],
        ]);
    }
}
