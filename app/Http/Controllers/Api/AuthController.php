<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;         // Para recibir los datos (email y password) desde el cliente
use App\Models\User;                 // Para buscar al usuario en la base de datos
use Illuminate\Support\Facades\Hash; // Para verificar la contraseña con Bcrypt

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validamos que nos envíen el correo y la contraseña obligatoriamente
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Buscamos al usuario en la tabla por su email
        $user = User::where('email', $request->email)->first();

        // 3. Si el usuario no existe O la contraseña no coincide con el password_hash de la BD
        if (! $user || ! Hash::driver('bcrypt')->check($request->password, $user->password_hash)) {
            return response()->json([
                'message' => 'Las credenciales introducidas son incorrectas.'
            ], 401); // 401 significa "No autorizado"
        }

        // 4. Si el usuario está desactivado en el sistema
        if (!$user->is_active) {
            return response()->json([
                'message' => 'Este usuario está deshabilitado.'
            ], 403); // 403 significa "Prohibido el acceso"
        }

        // 5. Generamos el token de acceso seguro usando Sanctum (el trait que activamos en el User.php)
        $token = $user->createToken('angular_token')->plainTextToken;

        // 6. Devolvemos la respuesta con el usuario y su token de acceso
        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 200); // 200 significa "Éxito"
    }
    
    public function logout(Request $request)
    {
        // 1. Identificamos el token que está usando el usuario en este momento y lo eliminamos de la base de datos
        $request->user()->currentAccessToken()->delete();

        // 2. Devolvemos un mensaje de éxito
        return response()->json([
            'message' => 'Sesión cerrada correctamente y token eliminado.'
        ], 200);
    }

    public function me(Request $request)
    {
        // Este método simplemente devuelve toda la información del usuario que hizo la petición.
        // Es muy útil para el frontend cuando el usuario recarga la página y Angular necesita saber quién está conectado.
        return response()->json(
            $request->user()
        , 200);
    }

}

