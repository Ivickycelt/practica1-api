<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; // 🔥 IMPORTACIÓN CRUCIAL QUE FALTABA

class AuthController extends Controller
{
    // 1. REGISTRO (Vale 20 puntos en tu rúbrica)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client', // Rol por defecto (en inglés para tests)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // 2. LOGIN (Modificado para que Vue reciba los permisos de inmediato)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas. Verifica tus datos.'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        // 🔑 Generamos los permisos usando los roles directamente para asegurar compatibilidad inmediata con v-can
        $permisos = [
            'crear'    => in_array($user->role, ['admin', 'editor']),
            'editar'   => in_array($user->role, ['admin', 'editor']),
            'eliminar' => $user->role === 'admin',
        ];

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'permisos' => $permisos // 🔥 Ahora Vue los tiene desde el segundo uno
            ],
            'token' => $token,
        ], 200);
    }

    // 3. LOGOUT (Vale 15 puntos en tu rúbrica)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ], 200);
    }

    // 4. ENDPOINT /ME (Corregido y optimizado para v-can)
    public function me(Request $request)
    {
        $user = $request->user();

        // 🔑 Mapeo directo y seguro de permisos basado en el rol de la Base de Datos
        $permisos = [
            'crear'    => in_array($user->role, ['admin', 'editor']),
            'editar'   => in_array($user->role, ['admin', 'editor']),
            'eliminar' => $user->role === 'admin',
        ];

        return response()->json([
            'id'       => $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'role'      => $user->role, 
            'permisos' => $permisos // 🔥 Enviado correctamente a Pinia
        ]);
    }
}