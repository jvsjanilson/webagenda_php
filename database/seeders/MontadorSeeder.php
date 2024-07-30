<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MontadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $montador = DB::table('users')->insertGetId(
            [
                'name' => 'Janilson',
                'email'=> 'janilsonjvs@gmail.com',
                'password' => bcrypt('@jvs123!'),
                'superuser' => 0,
                'montador' => 1
            ], //destak
        );

        DB::table('user_empresas')->insert([
            ['empresa_id' => 1, 'user_id' => $montador], //destak
            // ['empresa_id' => 2, 'user_id' => 3], //ziglar
            // ['empresa_id' => 3, 'user_id' => 4], //sono e conforto
            // ['empresa_id' => 4, 'user_id' => 5], //sono prime

        ]);


    }
}
