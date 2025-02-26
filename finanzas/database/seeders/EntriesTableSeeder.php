<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EntriesTableSeeder extends Seeder
{
    public function run()
    {
        $userId = 1; // Cambia esto al ID de un usuario existente en tu base de datos

        DB::table('entries')->insert([
            [
                'id_user' => $userId,
                'source' => 'Salario',
                'description' => 'Pago mensual',
                'amount' => 5000000.00,
                'date' => Carbon::now()->subMonths(3)->toDateString(),
            ],
            [
                'id_user' => $userId,
                'source' => 'Freelance',
                'description' => 'Proyecto de desarrollo web',
                'amount' => 1500000.00,
                'date' => Carbon::now()->subMonths(2)->toDateString(),
            ],
            [
                'id_user' => $userId,
                'source' => 'Salario',
                'description' => 'Pago mensual',
                'amount' => 5000000.00,
                'date' => Carbon::now()->subMonths(1)->toDateString(),
            ],
            [
                'id_user' => $userId,
                'source' => 'Salario',
                'description' => 'Pago mensual',
                'amount' => 5000000.00,
                'date' => Carbon::now()->toDateString(),
            ],
        ]);
    }
}