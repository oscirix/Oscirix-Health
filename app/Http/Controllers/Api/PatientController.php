<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        // Filtramos por la clínica del usuario autenticado
        $clinicId = $request->user()->clinic_id;
        
        $patients = Patient::where('clinic_id', $clinicId)->paginate(15);

        return response()->json($patients, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hcu_number' => 'nullable|string|max:50',
            'document_id' => 'nullable|string|max:50',
            'first_last_name' => 'required|string|max:100',
            'second_last_name' => 'nullable|string|max:100',
            'first_name' => 'required|string|max:100',
            'second_name' => 'nullable|string|max:100',
            'full_name' => 'nullable|string|max:255',
            'sex' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'blood_type' => 'nullable|string|max:10',
            'whatsapp_phone' => 'nullable|string|max:50',
            'phone_extension' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'avatar_url' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:50',
        ]);

        // Asignamos el clinic_id del usuario logueado
        $validated['clinic_id'] = $request->user()->clinic_id;

        // Autogenerar full_name si está vacío
        if (empty($validated['full_name'])) {
            $validated['full_name'] = trim(($validated['first_name'] ?? '') . ' ' . ($validated['first_last_name'] ?? ''));
        }

        // Crear una instancia limpia en lugar de usar create directo
        $patient = new Patient();
        $patient->fill($validated);
        $patient->save();

        return response()->json([
            'message' => 'Paciente creado exitosamente.',
            'patient' => $patient
        ], 201);
    }

    public function show(Request $request, Patient $patient)
    {
        // Validar seguridad multi-clínica
        if ($patient->clinic_id !== $request->user()->clinic_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($patient, 200);
    }

    public function update(Request $request, Patient $patient)
    {
        if ($patient->clinic_id !== $request->user()->clinic_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $validated = $request->validate([
            'hcu_number' => 'nullable|string|max:50',
            'document_id' => 'nullable|string|max:50',
            'first_last_name' => 'sometimes|string|max:100',
            'second_last_name' => 'nullable|string|max:100',
            'first_name' => 'sometimes|string|max:100',
            'second_name' => 'nullable|string|max:100',
            'full_name' => 'nullable|string|max:255',
            'sex' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'blood_type' => 'nullable|string|max:10',
            'whatsapp_phone' => 'nullable|string|max:50',
            'phone_extension' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'avatar_url' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:50',
        ]);

        $patient->update($validated);

        return response()->json([
            'message' => 'Paciente actualizado exitosamente.',
            'patient' => $patient
        ], 200);
    }

    public function summary(Request $request, Patient $patient)
    {
        if ($patient->clinic_id !== $request->user()->clinic_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json([
            'patient_id' => $patient->id,
            'summary' => 'Resumen clínico general del paciente'
        ], 200);
    }

    public function alerts(Request $request, Patient $patient)
    {
        if ($patient->clinic_id !== $request->user()->clinic_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json([], 200);
    }
}