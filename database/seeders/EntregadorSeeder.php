<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntregadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entregador = DB::table('users')->insertGetId(
            [
                'name' => 'Entregador',
                'email'=> 'destakmagazineentrega@gmail.com',
                'password' => bcrypt('@entregador123'),
                'superuser' => 0,
                'montador' => 1
            ], //destak
        );

        foreach (Empresa::all() as $p) {
            DB::table('user_empresas')->insert([
                    'empresa_id' => $p->id,
                    'user_id' => $entregador
                ]
            );
        }
    }
}
