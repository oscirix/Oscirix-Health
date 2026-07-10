<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    // Listar leads filtrados por la clínica del usuario autenticado
    public function index(Request $request)
    {
        $clinicId = $request->user()->clinic_id;

        $leads = Lead::where('clinic_id', $clinicId)->paginate(15);

        return response()->json($leads, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:160',
            'whatsapp_phone' => 'nullable|string|max:40',
            'phone_extension' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'treatment_id' => 'nullable|string|size:36',
            'chat_summary' => 'nullable|string',
            'status' => 'nullable|in:new,qualified,converted,lost',
            'converted_patient_id' => 'nullable|string|size:36',
            'converted_appointment_id' => 'nullable|string|size:36',
        ]);

        // Asignamos automáticamente el clinic_id del usuario logueado
        $validated['clinic_id'] = $request->user()->clinic_id;

        $lead = new Lead();
        $lead->fill($validated);
        $lead->save();

        return response()->json([
            'message' => 'Lead creado exitosamente.',
            'lead' => $lead
        ], 201);
    }


    // Actualizar un lead existente
    public function update(Request $request, Lead $lead)
    {
        // Validar seguridad multi-clínica
        if ($lead->clinic_id !== $request->user()->clinic_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $validated = $request->validate([
            'full_name' => 'sometimes|string|max:160',
            'whatsapp_phone' => 'nullable|string|max:40',
            'phone_extension' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'treatment_id' => 'nullable|string|size:36',
            'chat_summary' => 'nullable|string',
            'status' => 'nullable|in:new,qualified,converted,lost',
            'converted_patient_id' => 'nullable|string|size:36',
            'converted_appointment_id' => 'nullable|string|size:36',
        ]);

        $lead->update($validated);

        return response()->json([
            'message' => 'Lead actualizado exitosamente.',
            'lead' => $lead
        ], 200);
    }
}