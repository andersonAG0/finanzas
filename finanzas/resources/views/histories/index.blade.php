@extends('layouts.menu')

@section('title', 'Historial Financiero')

@section('content')
<div class="flex justify-center w-full p-6">
    <div class="w-full max-w-2xl">
        <h1 class="text-3xl font-bold mb-4 text-center">Historial Financiero Mensual</h1>

        <div x-data="modalHandler()">
            <table class="w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 text-center w-1/5">Mes</th>
                        <th class="p-3 text-center w-1/5">Año</th>
                        <th class="p-3 text-center w-1/5">Ingresos</th>
                        <th class="p-3 text-center w-1/5">Salidas</th>
                        <th class="p-3 text-center w-1/5">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                        <tr class="cursor-pointer" @click="fetchDetails({{ $history->month }}, {{ $history->year }})">
                            <td class="p-3 text-center">{{ \Carbon\Carbon::create()->locale('es')->month($history->month)->translatedFormat('F') }}</td>
                            <td class="p-3 text-center">{{ $history->year }}</td>
                            <td class="p-3 text-center text-green-600 font-bold">{{ $history->total_entries }}</td>
                            <td class="p-3 text-center text-red-600 font-bold">{{ $history->total_expenses }}</td>
                            <td class="p-3 text-center font-bold {{ $history->total_entries - $history->total_expenses >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $history->total_entries - $history->total_expenses }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        
            <table class="w-full bg-white shadow-md rounded-lg mt-2">
                <tbody>
                    <tr class="border-b">
                        <td class="p-3 w-1/5"></td>
                        <td class="p-3 text-center font-bold w-1/5">Total</td>
                        <td class="p-3 text-center text-green-600 font-bold w-1/5">{{ $histories->sum('total_entries') }}</td>
                        <td class="p-3 text-center text-red-600 font-bold w-1/5">{{ $histories->sum('total_expenses') }}</td>
                        <td class="p-3 text-center font-bold w-1/5 {{ $histories->sum('total_entries') - $histories->sum('total_expenses') >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $histories->sum('total_entries') - $histories->sum('total_expenses') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        
            <template x-if="showModal">
                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
                        <h2 class="text-2xl font-bold mb-4">Detalles del Mes</h2>
        
                        <p><strong>Mes:</strong> <span x-text="selectedHistory.month"></span></p>
                        <p><strong>Año:</strong> <span x-text="selectedHistory.year"></span></p>
        
                        <p><strong>Ingresos:</strong></p>
                        <ul>
                            <template x-for="entry in details.entries" :key="entry.id">
                                <li x-text="entry.date + ': ' + '$' + entry.amount"></li>
                            </template>
                        </ul>
        
                        <p><strong>Salidas:</strong></p>
                        <ul>
                            <template x-for="expense in details.expenses" :key="expense.id">
                                <li x-text="expense.date + ': ' + '$' + expense.amount"></li>
                            </template>
                        </ul>
        
                        <p><strong>Balance:</strong> <span x-text="'$' + (selectedHistory.total_entries - selectedHistory.total_expenses)"></span></p>
        
                        <button @click="showModal = false" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg">Cerrar</button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>


<script>
    function modalHandler() {
        return {
            showModal: false,
            selectedHistory: { month: '', year: '', total_entries: 0, total_expenses: 0 },
            details: { entries: [], expenses: [] },
            fetchDetails(month, year) {
                fetch(`/histories/details/${month}/${year}`)
                    .then(response => response.json())
                    .then(data => {
                        this.selectedHistory = { 
                            month: month, 
                            year: year, 
                            total_entries: data.entries.reduce((sum, entry) => sum + Number(entry.amount), 0), 
                            total_expenses: data.expenses.reduce((sum, expense) => sum + Number(expense.amount), 0) 
                        };
                        this.details = data;
                        this.showModal = true;
                    });
            }
        }
    }
</script>
@endsection