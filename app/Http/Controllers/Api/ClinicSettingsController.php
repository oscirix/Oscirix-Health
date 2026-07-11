<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicSetting;
use Illuminate\Http\Request;

class ClinicSettingsController extends Controller
{
    // Mostrar la configuración única de la clínica
    public function show()
    {
        $settings = ClinicSetting::first();

        if (!$settings) {
            return response()->json([
                'message' => 'Aún no hay configuraciones registradas. Usa PUT para crearlas.'
            ], 200);
        }

        return response()->json($settings, 200);
    }

    // Actualizar o crear la configuración única de la clínica
    public function update(Request $request)
    {
        $validated = $request->validate([
            'booking_notice_min' => 'sometimes|integer|min:0',
            'cancel_notice_min' => 'sometimes|integer|min:0',
            'allow_web_booking' => 'boolean',
            'allow_whatsapp_booking' => 'boolean',
            'notifications_enabled' => 'boolean',
        ]);

        $settings = ClinicSetting::first();

        if (!$settings) {
            $settings = ClinicSetting::create($validated);
            $message = 'Configuración clínica creada exitosamente.';
        } else {
            $settings->update($validated);
            $message = 'Configuración clínica actualizada exitosamente.';
        }

        return response()->json([
            'message' => $message,
            'clinic_settings' => $settings
        ], 200);
    }
}