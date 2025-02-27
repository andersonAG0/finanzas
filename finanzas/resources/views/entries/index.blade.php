@extends('layouts.menu')

@section('title', 'Ingresos')

@section('content')
    <div class="flex-1 p-10" x-data="{ showForm: false, editMode: false, entry: {} }">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Ingresos</h1>

        <button @click="showForm = true; editMode = false; entry = {}" 
            class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-600 transition">
            + Agregar Ingreso
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
                    <span x-text="editMode ? 'Editar Ingreso' : 'Agregar Ingreso'"></span>
                </h2>
                
                <form method="POST" x-bind:action="editMode ? '{{ route('entries.update', '__ID__') }}'.replace('__ID__', entry.id) : '{{ route('entries.store') }}'">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    <input type="hidden" name="id" x-model="entry.id">
                
                    <div class="mb-4">
                        <label class="block text-gray-700">Fuente</label>
                        <input type="text" name="source" x-model="entry.source" class="w-full p-2 border rounded-lg">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Descripción</label>
                        <textarea name="description" x-model="entry.description" class="w-full p-2 border rounded-lg"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Monto</label>
                        <input type="number" name="amount" x-model="entry.amount" class="w-full p-2 border rounded-lg">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Fecha</label>
                        <input type="date" name="date" x-model="entry.date" class="w-full p-2 border rounded-lg">
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

        <!-- Tabla de ingresos -->
        <div class="mt-6" x-show="!showForm" x-transition:enter="transition ease-out duration-300 transform" 
            x-transition:enter-start="opacity-0 -translate-x-full" 
            x-transition:enter-end="opacity-100 translate-x-0" 
            x-transition:leave="transition ease-in duration-200 transform" 
            x-transition:leave-start="opacity-100 translate-x-0" 
            x-transition:leave-end="opacity-0 -translate-x-full">
            <table class="w-full bg-white rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3">Fuente</th>
                        <th class="p-3">Descripción</th>
                        <th class="p-3">Monto</th>
                        <th class="p-3">Fecha</th>
                        <th class="p-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr class="border-b">
                            <td class="p-3">{{ $entry->source }}</td>
                            <td class="p-3">{{ $entry->description }}</td>
                            <td class="p-3">${{ number_format($entry->amount, 2) }}</td>
                            <td class="p-3">{{ $entry->date }}</td>
                            <td class="p-3">
                                <button @click="showForm = true; editMode = true; entry = { 
                                    id: '{{ $entry->id }}',
                                    source: '{{ $entry->source }}',
                                    description: '{{ $entry->description }}',
                                    amount: '{{ $entry->amount }}',
                                    date: '{{ $entry->date }}'
                                }" 
                                    class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition">
                                    Editar
                                </button>
                                <button type="button" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition" onclick="confirmDelete({{ $entry->id }})">
                                    Eliminar
                                </button>
                                <form id="delete-form-{{ $entry->id }}" action="{{ route('entries.destroy', $entry->id) }}" method="POST" class="hidden">
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
        function confirmDelete(entryId) {
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
                    document.getElementById('delete-form-' + entryId).submit();
                }
            })
        }
    </script>
@endsection