<?php

namespace App\Observers;

use App\Models\Expense;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ExpenseObserver
{
    public function created(Expense $expense)
    {
        Log::info('Creando Expense', ['id' => $expense->id]);
        $this->updateHistory($expense, 1);
    }

    public function deleted(Expense $expense)
    {
        Log::info('Eliminando Expense', ['id' => $expense->id]);
        $this->updateHistory($expense, -1);
    }

    public function updated(Expense $expense)
    {
        if ($expense->isDirty('date')) {
            // Si cambió la fecha, quitamos de la fecha vieja y sumamos a la nueva
            $originalDate = $expense->getOriginal('date');
            $originalExpense = clone $expense;
            $originalExpense->date = $originalDate;

            Log::info('Fecha cambiada en Expense', [
                'id' => $expense->id,
                'old_date' => $originalDate,
                'new_date' => $expense->date,
            ]);

            // Quitar del historial anterior
            $this->updateHistory($originalExpense, -1);

            // Agregar al nuevo historial
            $this->updateHistory($expense, 1);
        }
    }

    private function updateHistory(Expense $expense, int $change)
    {
        $date = Carbon::parse($expense->date);

        $history = History::firstOrCreate([
            'id_user' => $expense->id_user,
            'month' => $date->month,
            'year' => $date->year,
        ], [
            'total_entries' => 0,
            'total_expenses' => 0,
        ]);

        $history->total_expenses = max(0, $history->total_expenses + $change);
        $history->save();

        Log::info('Historial actualizado (Expenses)', [
            'user_id' => $expense->id_user,
            'month' => $date->month,
            'year' => $date->year,
            'total_entries' => $history->total_entries,
            'total_expenses' => $history->total_expenses,
        ]);
    }
}
