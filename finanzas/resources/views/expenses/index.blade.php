@extends('layouts.menu')

@section('title', 'Salidas')

@section('content')
    <div class="flex-1 p-10" x-data="{ showForm: false, editMode: false, expense: {} }">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Salidas</h1>

        <button @click="showForm = true; editMode = false; expense = {}" 
            class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-600 transition">
            + Agregar Salida
        </button>

        <!-- Formulario -->
        <div x-show="showForm" x-cloak x-transition:enter="transition ease-out duration-300 transform" 
            x-transition:enter-start="opacity-0 translate-x-full" 
            x-transition:enter-end="opacity-100 translate-x-0" 
            x-transition:leave="transition ease-in duration-200 transform" 
            x-transition:leave-start="opacity-100 translate-x-0" 
            x-transition:leave-end="opacity-0 translate-x-full" 
            class="mt-6 items-center justify-center flex">
            <div class="bg-white p-6 rounded-lg shadow- w-96 transform transition-all">
                <h2 class="text-xl font-bold mb-4">
                    <span x-text="editMode ? 'Editar Salida' : 'Agregar Salida'"></span>
                </h2>
                
                <form method="POST" x-bind:action="editMode ? '{{ route('expenses.update', '__ID__') }}'.replace('__ID__', expense.id) : '{{ route('expenses.store') }}'">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    <input type="hidden" name="id" x-model="expense.id">
                
                    <div class="mb-4">
                        <label class="block text-gray-700">Categoría</label>
                        <input type="text" name="category" x-model="expense.category" class="w-full p-2 border rounded-lg">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Descripción</label>
                        <textarea name="description" x-model="expense.description" class="w-full p-2 border rounded-lg"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Tipo</label>
                        <select name="type" x-model="expense.type" class="w-full p-2 border rounded-lg">
                            <option value="variable">Variable</option>
                            <option value="fijo">Fijo</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Monto</label>
                        <input type="number" name="amount" x-model="expense.amount" class="w-full p-2 border rounded-lg">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Fecha</label>
                        <input type="date" name="date" x-model="expense.date" class="w-full p-2 border rounded-lg">
                    </div>
                
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            Guardar
                        </button>
                        <button type="button" @click="showForm = false" class="bg-gray-400 text-white px-4 py-2 rounded-lg ml-2 hover:bg-gray-500 transition">
                            Cancelar
                        </button>
                    </div>
                </form>                
            </div>
        </div>

        <!-- Tabla de salidas -->
        <div class="mt-6" x-show="!showForm" x-transition:enter="transition ease-out duration-300 transform" 
            x-transition:enter-start="opacity-0 -translate-x-full" 
            x-transition:enter-end="opacity-100 translate-x-0" 
            x-transition:leave="transition ease-in duration-200 transform" 
            x-transition:leave-start="opacity-100 translate-x-0" 
            x-transition:leave-end="opacity-0 -translate-x-full">
            <table class="w-full bg-white rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3">Categoría</th>
                        <th class="p-3">Descripción</th>
                        <th class="p-3">Tipo</th>
                        <th class="p-3">Monto</th>
                        <th class="p-3">Fecha</th>
                        <th class="p-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                        <tr class="border-b">
                            <td class="p-3">{{ $expense->category }}</td>
                            <td class="p-3">{{ $expense->description }}</td>
                            <td class="p-3">{{ ucfirst($expense->type) }}</td>
                            <td class="p-3">${{ number_format($expense->amount, 2) }}</td>
                            <td class="p-3">{{ $expense->date }}</td>
                            <td class="p-3">
                                <button @click="showForm = true; editMode = true; expense = { 
                                    id: '{{ $expense->id }}',
                                    category: '{{ $expense->category }}',
                                    description: '{{ $expense->description }}',
                                    type: '{{ $expense->type }}',
                                    amount: '{{ $expense->amount }}',
                                    date: '{{ $expense->date }}'
                                }" 
                                    class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition">
                                    Editar
                                </button>
                                <button type="button" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition" onclick="confirmDelete({{ $expense->id }})">
                                    Eliminar
                                </button>
                                <form id="delete-form-{{ $expense->id }}" action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                position: 'bottom-start',
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                background: 'rgba(34, 79, 54)',
                customClass: {
                    popup: 'rounded-lg text-white'
                }
            });
        </script>
    @endif

    <script>
        function confirmDelete(expenseId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + expenseId).submit();
                }
            })
        }
    </script>
@endsection
