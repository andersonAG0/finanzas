<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrie;
use Illuminate\Support\Facades\Auth;

class EntriesController extends Controller
{
    public function index()
    {
        $entries = Entrie::where('id_user', Auth::id())->orderBy('date', 'asc')->get();
        $totalEntries = $entries->sum('amount');
        
        return view('entries.index', compact('entries', 'totalEntries'));
    }

    public function create()
    {
        return view('entries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'source' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Entrie::create([
            'id_user' => Auth::id(),
            'source' => $request->source,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('entries.index')->with('success', 'Ingreso agregado correctamente.');
    }

    public function edit(Entrie $entrie)
    {
        if ($entrie->id_user !== Auth::id()) {
            abort(403);
        }
        return view('entries.edit', compact('entrie'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'source' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $entrie = Entrie::find($id);

        $entrie->source = $request->source;
        $entrie->description = $request->description;
        $entrie->amount = $request->amount;
        $entrie->date = $request->date;
        $entrie->save();

        return redirect()->route('entries.index')->with('success', 'Ingreso actualizado correctamente.');
    }

    public function destroy($id)
    {
        $entrie = Entrie::find($id);

        $entrie->delete();

        return redirect()->route('entries.index')->with('success', 'Ingreso eliminado correctamente.');
    }
}
