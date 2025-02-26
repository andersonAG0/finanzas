@extends('layouts.auth')

@section('title', 'Registro')

@section('content')
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-96 flex flex-col items-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-green-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM3.75 21a8.25 8.25 0 0116.5 0H3.75z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Registro</h2>
            <form action="{{ route('register-api') }}" method="POST">
                @csrf
                <div class="relative mb-3">
                    <i class="fa-solid fa-file-signature absolute left-3 top-3 text-gray-500"></i>
                    <input type="text" name="name" placeholder="Nombre Completo" class="w-full pl-10 p-2 border rounded-lg focus:ring-10" required>
                </div>
                <div class="relative mb-3">
                    <i class="fa-solid fa-calendar-days absolute left-3 top-3 text-gray-500"></i>
                    <input type="number" name="age" placeholder="Edad" class="w-full pl-10 p-2 border rounded-lg focus:ring-10" required>
                </div>
                <div class="relative mb-3">
                    <i class="fa-solid fa-circle-user absolute left-3 top-3 text-gray-500"></i>
                    <input type="text" name="username" placeholder="Usuario" class="w-full pl-10 p-2 border rounded-lg focus:ring-10" required>
                </div>
                <div class="relative mb-3">
                    <i class="fa-solid fa-lock absolute left-3 top-3 text-gray-500"></i>
                    <input type="password" name="password" placeholder="Contraseña" class="w-full pl-10 p-2 border rounded-lg focus:ring-10" required>
                </div>
                <div class="relative mb-4">
                    <i class="fa-solid fa-envelope absolute left-3 top-3 text-gray-500"></i>
                    <input type="email" name="email" placeholder="Email" class="w-full pl-10 p-2 border rounded-lg focus:ring-10" required>
                </div>
                <button type="submit" class="w-full bg-green-700 text-white p-2 rounded-lg hover:bg-green-600 transition duration-300 font-bold">
                    Registrarse
                </button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-4">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-green-700 hover:underline">Inicia Sesión</a></p>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                position: 'bottom-end',
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
@endsection