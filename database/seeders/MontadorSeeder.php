<?php

namespace Database\Seeders;

use App\Models\Empresa;
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
                'name' => 'Montador',
                'email'=> 'destakmagazinemontagem@gmail.com',
                'password' => bcrypt('@montador123'),
                'superuser' => 0,
                'montador' => 1,
                'entregador' => 0
            ],
        );

        foreach (Empresa::all() as $p) {
            DB::table('user_empresas')->insert([
                    'empresa_id' => $p->id,
                    'user_id' => $montador
                ]
            );
        }
    }
}
