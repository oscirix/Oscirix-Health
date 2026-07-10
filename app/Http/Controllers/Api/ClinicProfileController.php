<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicProfile;
use Illuminate\Http\Request;

class ClinicProfileController extends Controller
{
    // Mostrar la información del perfil clínico único
    public function show()
    {
        $profile = ClinicProfile::first();

        // Si aún no hay registros, devolvemos un JSON vacío en lugar de romper la app
        if (!$profile) {
            return response()->json([
                'message' => 'Aún no hay perfil clínico registrado. Usa PUT para crearlo.'
            ], 200);
        }

        return response()->json($profile, 200);
    }

    // Actualizar o crear el perfil clínico único
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:150',
            'brand_name' => 'sometimes|string|max:150',
            'specialty' => 'nullable|string|max:120',
            'phone' => 'nullable|string|max:40',
            'whatsapp_phone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'logo_url' => 'nullable|string|max:500',
            'primary_color' => 'nullable|string|max:20',
            'website_url' => 'nullable|string|max:500',
            'timezone' => 'nullable|string|max:80',
        ]);

        // Buscamos el perfil existente o lo creamos si está vacío
        $profile = ClinicProfile::first();

        if (!$profile) {
            $profile = ClinicProfile::create($validated);
            $message = 'Perfil clínico creado exitosamente.';
        } else {
            $profile->update($validated);
            $message = 'Perfil clínico actualizado exitosamente.';
        }

        return response()->json([
            'message' => $message,
            'clinic_profile' => $profile
        ], 200);
    }
}