<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpensesTableSeeder extends Seeder
{
    public function run()
    {
        $userId = 1;

        DB::table('expenses')->insert([
            [
                'id_user' => $userId,
                'category' => 'Alquiler',
                'description' => 'Pago mensual de alquiler',
                'type' => 'fijo',
                'amount' => 1200000.00,
                'date' => Carbon::now()->subMonths(3)->toDateString(),
            ],
            [
                'id_user' => $userId,
                'category' => 'Comida',
                'description' => 'Compras de supermercado',
                'type' => 'variable',
                'amount' => 800000.00,
                'date' => Carbon::now()->subMonths(2)->toDateString(),
            ],
            [
                'id_user' => $userId,
                'category' => 'Transporte',
                'description' => 'Gastos de transporte',
                'type' => 'variable',
                'amount' => 500000.00,
                'date' => Carbon::now()->subMonths(1)->toDateString(),
            ],
            [
                'id_user' => $userId,
                'category' => 'Otros',
                'description' => 'Gastos varios',
                'type' => 'variable',
                'amount' => 700000.00,
                'date' => Carbon::now()->toDateString(),
            ],
        ]);
    }
}