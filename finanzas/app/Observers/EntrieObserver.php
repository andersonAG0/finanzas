<?php

namespace App\Observers;

use App\Models\Entrie;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EntrieObserver
{
    public function created(Entrie $entrie)
    {
        $this->updateHistory($entrie->id_user, 1, 0, $entrie->date);
    }

    public function deleted(Entrie $entrie)
    {
        $this->updateHistory($entrie->id_user, -1, 0, $entrie->date);
    }

    public function updated(Entrie $entrie)
    {
        if ($entrie->wasChanged('date')) {
            // Fecha cambió => ajustar historial en dos meses/años diferentes
            $oldDate = Carbon::parse($entrie->getOriginal('date'));
            $newDate = Carbon::parse($entrie->date);

            Log::info('Fecha cambiada en Entrie', [
                'id' => $entrie->id,
                'old_date' => $oldDate->toDateString(),
                'new_date' => $newDate->toDateString(),
            ]);

            $this->updateHistory($entrie->id_user, -1, 0, $oldDate);

            $this->updateHistory($entrie->id_user, 1, 0, $newDate);
        } else {
            // Fecha no cambió, solo actualizamos algún dato (no afecta el historial mensual)
            Log::info('Entrie actualizado sin cambiar fecha', ['id' => $entrie->id]);
        }
    }

    private function updateHistory($userId, $entrieChange, $expenseChange, $date)
    {
        $date = Carbon::parse($date);

        $history = History::firstOrCreate([
            'id_user' => $userId,
            'month' => $date->month,
            'year' => $date->year,
        ], [
            'total_entries' => 0,
            'total_expenses' => 0,
        ]);

        $history->total_entries = max(0, $history->total_entries + $entrieChange);
        $history->total_expenses += $expenseChange;
        $history->save();

        Log::info('Historial actualizado', [
            'user_id' => $userId,
            'month' => $date->month,
            'year' => $date->year,
            'total_entries' => $history->total_entries,
            'total_expenses' => $history->total_expenses,
        ]);
    }
}
