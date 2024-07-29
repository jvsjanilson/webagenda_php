<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['name' => 'Administrador',  'email'=> 'contato@destakmoveisecolchoes.com.br', 'password' => bcrypt('@admin123'), 'superuser' => 1 ], //destak
            ['name' => 'Vendedor Destak',  'email'=> 'spindustriaecomercio@gmail.com', 'password' => bcrypt('@vendedor123'), 'superuser' => 0 ], //destak
            ['name' => 'Ziglar',  'email'=> 'ziglarmoveis@gmail.com', 'password' => bcrypt('@ziglar123'), 'superuser' => 0 ], //ziglar
            ['name' => 'Sono e Conforto',  'email'=> 'sonoeconfortorn@hotmail.com', 'password' => bcrypt('@sonoc123'), 'superuser' => 0 ], //sono conforto
            ['name' => 'Sono Prime',  'email'=> 'sonoeconfortoprime@hotmail.com ', 'password' => bcrypt('@sonop123'), 'superuser' => 0 ], //sono prime
        ]);
    }
}
