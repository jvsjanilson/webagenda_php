<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MontadorSeederV2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('montadors')->insert([
            [
                'nome' => 'UBIRAJARA',
            ],
            [
                'nome' => 'GARCIA',
            ]
        ]);

    }
}
