@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-96 flex flex-col items-center">
        <div class="mb-4">
            <i class="ph ph-wallet text-green-600 text-5xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-700">Iniciar Sesión</h2>
        <p class="text-gray-500 mb-4">Administra tus finanzas con seguridad</p>
        <form action="{{ route('login-api') }}" method="POST" class="w-full">
            @csrf
            <div class="relative mb-3">
                <i class="fa-solid fa-circle-user absolute left-3 top-3 text-gray-500"></i>
                <input type="text" name="username" placeholder="Usuario" class="w-full pl-10 p-2 border rounded-lg focus:ring-10" required>
            </div>
            <div class="relative mb-4">
                <i class="fa-solid fa-lock absolute left-3 top-3 text-gray-500"></i>
                <input type="password" name="password" placeholder="Contraseña" class="w-full pl-10 p-2 border rounded-lg focus:ring-10" required>
            </div>
            <button type="submit" class="w-full bg-green-700 text-white p-2 rounded-lg hover:bg-green-600 transition font-bold">Ingresar</button>
        </form>
        <p class="text-sm text-gray-500 mt-4">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-green-700 hover:underline">Regístrate</a></p>
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

    @if(session('error'))
        <script>
            Swal.fire({
                position: 'bottom-start',
                icon: 'error',
                title: '¡Error!',
                text: '{{ session('error') }}',
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

    @if($errors->any())
        <script>
            Swal.fire({
                position: 'bottom-start',
                icon: 'error',
                title: '¡Error!',
                text: '{{ $errors->first() }}',
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