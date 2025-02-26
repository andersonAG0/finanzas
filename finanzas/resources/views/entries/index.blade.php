@extends('layouts.menu')

@section('title', 'Ingresos')

@section('content')
    <div class="flex-1 p-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Ingresos</h1>
        
        <button id="openForm" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-600 transition">+ Agregar Ingreso</button>
        
        <div id="entryForm" class="fixed top-0 left-[-400px] w-96 h-full bg-white shadow-lg p-6 transition-transform duration-300 z-50">
            <h2 class="text-xl font-bold mb-4">Agregar / Editar Ingreso</h2>
            <form id="entryFormContent" method="POST" action="{{ route('entries.store') }}">
                @csrf
                <input type="hidden" name="id_entrie" id="entryId">
                
                <div class="mb-4">
                    <label class="block text-gray-700">Fuente</label>
                    <input type="text" name="source" id="source" class="w-full p-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Descripción</label>
                    <textarea name="description" id="description" class="w-full p-2 border rounded-lg"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Monto</label>
                    <input type="number" name="amount" id="amount" class="w-full p-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Fecha</label>
                    <input type="date" name="date" id="date" class="w-full p-2 border rounded-lg">
                </div>
                
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Guardar</button>
                <button type="button" id="closeForm" class="bg-gray-400 text-white px-4 py-2 rounded-lg ml-2 hover:bg-gray-500 transition">Cancelar</button>
            </form>
        </div>

        <div class="mt-6">
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
                                <button class="editEntry bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition" data-entry='@json($entry)'>Editar</button>
                                <form action="{{ route('entries.destroy', $entry->id_entrie) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('openForm').addEventListener('click', function() {
            document.getElementById('entryForm').style.transform = 'translateX(400px)';
        });
        
        document.getElementById('closeForm').addEventListener('click', function() {
            document.getElementById('entryForm').style.transform = 'translateX(-400px)';
        });
        
        document.querySelectorAll('.editEntry').forEach(button => {
            button.addEventListener('click', function() {
                let entry = JSON.parse(this.dataset.entry);
                document.getElementById('entryId').value = entry.id_entrie;
                document.getElementById('source').value = entry.source;
                document.getElementById('description').value = entry.description;
                document.getElementById('amount').value = entry.amount;
                document.getElementById('date').value = entry.date;
                document.getElementById('entryFormContent').action = `{{ url('entries') }}/${entry.id_entrie}`;
                document.getElementById('entryForm').style.transform = 'translateX(400px)';
            });
        });
    </script>
@endsection
