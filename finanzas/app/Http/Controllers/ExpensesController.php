<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('id_user', Auth::id())->orderBy('date', 'asc')->get();
        $totalExpenses = $expenses->sum('amount');

        return view('expenses.index', compact('expenses', 'totalExpenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:variable,fijo',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Expense::create([
            'id_user' => Auth::id(),
            'category' => $request->category,
            'description' => $request->description,
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Salida agregada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:variable,fijo',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $expense = Expense::findOrFail($id);

        if ($expense->id_user !== Auth::id()) {
            abort(403);
        }

        $expense->category = $request->category;
        $expense->description = $request->description;
        $expense->type = $request->type;
        $expense->amount = $request->amount;
        $expense->date = $request->date;
        $expense->save();

        return redirect()->route('expenses.index')->with('success', 'Salida actualizada correctamente.');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);

        if ($expense->id_user !== Auth::id()) {
            abort(403);
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Salida eliminada correctamente.');
    }
}
