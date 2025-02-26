<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'username' => 'required|string|min:3|unique:users',
            'password' => 'required|string|min:3',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user = User::create([
            'name' => $request->name,
            'age' => $request->age,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
        ]);

        return redirect()->route('login')->with('success', 'Registro exitoso. Por favor, inicie sesión.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $user->tokens()->delete();
        Auth::login($user);

        if (Auth::check()) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return redirect()->route('dashboard')->with('success', 'Inicio de sesión exitoso.');
        } else {
            return redirect()->route('login')->withErrors(['login' => 'La autenticación falló.']);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with(['success' => 'Sesión cerrada.']);
    }
}
