<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Finanzas')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-green-200 to-green-100">
    <div class="flex h-[90vh] w-[90vw] bg-gray-100 rounded-3xl overflow-auto no-scrollbar">
        <div class="absolute top-2 left-6 text-2xl font-bold">
            Bienvenido, {{ Auth::user()->name }}
        </div>
        <button type="button" class="absolute top-4 right-6" onclick="logout()">
            <i class="fa-solid fa-right-from-bracket text-3xl cursor-pointer text-gray-700 hover:text-red-600"></i>
        </button>
        <form id="logout-form" action="{{ route('logout') }}" class="absolute top-4 right-6" method="POST" class="hidden">
            @csrf
        </form>
        <div class="w-64 bg-white p-6 rounded-3xl sticky top-0">
            <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Finanzas</h2>
            <nav>
                <ul class="space-y-4 text-xl">
                    <li class="flex items-center justify-center space-x-2 bg-gradient-to-r from-green-200 to-green-100 rounded-lg hover:from-green-300 cursor-pointer p-1">
                        <i class="fa-solid fa-scroll"></i>
                        <a href="{{ route('dashboard') }}">Resumen</a>
                    </li>
                    <li class="flex items-center justify-center space-x-2 bg-gradient-to-r from-green-200 to-green-100 rounded-lg hover:from-green-300 cursor-pointer p-3">
                        <i class="fa-regular fa-credit-card"></i>
                        <a href="{{ route('entries.index') }}">Ingresos</a>
                    </li>
                    <li class="flex items-center justify-center space-x-2 bg-gradient-to-r from-green-200 to-green-100 rounded-lg hover:from-green-300 cursor-pointer p-3">
                        <i class="fa-solid fa-coins"></i>
                        <a href="{{ route('expenses.index') }}">Gastos</a>
                    </li>
                    <li class="flex items-center justify-center space-x-2 bg-gradient-to-r from-green-200 to-green-100 rounded-lg hover:from-green-300 cursor-pointer p-3">
                        <i class="fa-solid fa-newspaper"></i>
                        <a href="{{ route('histories.index') }}">Historial</a>
                    </li>
                    <li class="flex items-center justify-center space-x-2 bg-gradient-to-r from-green-200 to-green-100 rounded-lg hover:from-green-300 cursor-pointer p-3">
                        <i class="fa-solid fa-comments-dollar"></i>
                        <a href="{{ route('chat') }}">Chat Finanzas</a>
                    </li>
                </ul>
            </nav>
        </div>

        @yield('content')
    </div>

    <script>
        function logout() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Cerrarás la sesión actual',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Cerrar Sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
