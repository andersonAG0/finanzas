<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Support\Facades\Auth;
use App\Models\Entrie;
use App\Models\Expense;

class HistoriesController extends Controller
{
    public function index()
    {
        $histories = History::where('id_user', Auth::id())->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        return view('histories.index', compact('histories'));
    }

    public function getDetails($month, $year)
    {
        $entries = Entrie::whereMonth('date', $month)->whereYear('date', $year)->get();
        $expenses = Expense::whereMonth('date', $month)->whereYear('date', $year)->get();

        return response()->json([
            'entries' => $entries,
            'expenses' => $expenses,
        ]);
    }
}
