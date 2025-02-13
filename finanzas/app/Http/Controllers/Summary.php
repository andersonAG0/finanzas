<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Summary extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $totalEntries = DB::table('entries')
            ->where('id_user', $userId)
            ->sum('amount');

        $totalExpenses = DB::table('expenses')
            ->where('id_user', $userId)
            ->sum('amount');

        $balance = $totalEntries - $totalExpenses;

        $incomeData = DB::table('entries')
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->where('id_user', $userId)
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $expenseData = DB::table('expenses')
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->where('id_user', $userId)
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $expenseDistribution = DB::table('expenses')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->where('id_user', $userId)
            ->groupBy('category')
            ->pluck('total', 'category')->toArray();

        return view('summary', compact('totalEntries', 'totalExpenses', 'balance', 'incomeData', 'expenseData', 'expenseDistribution'));
    }
}
