<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecord;
use Illuminate\Http\Request;

class ClinicalRecordController extends Controller
{
    // Listar historias clínicas (GET), con filtros opcionales por patient_id o clinic_id
    public function index(Request $request, $patientId = null)
    {
        $query = ClinicalRecord::query();

        if ($patientId) {
            $query->where('patient_id', $patientId);
        } elseif ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('clinic_id')) {
            $query->where('clinic_id', $request->clinic_id);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get(), 200);
    }

    public function store(Request $request, $patientId = null)
    {
        // Si viene en la URL, inyectamos el patient_id en los datos a validar/crear
        if ($patientId) {
            $request->merge(['patient_id' => $patientId]);
        }

        $validated = $request->validate([
            'clinic_id' => 'required|string|size:36',
            'patient_id' => 'required|string|size:36',
            'appointment_id' => 'nullable|string|size:36',
            'record_number' => 'required|string|max:50',
            'record_type' => 'required|string|max:50',
            'origin' => 'nullable|string|max:50',
            'record_date' => 'required|date',
            'title' => 'required|string|max:160',
            'chief_complaint' => 'nullable|string',
            'current_illness' => 'nullable|string',
            'asa_classification' => 'nullable|string|max:20',
            'risk_medical' => 'nullable|string',
            'risk_periodontal' => 'nullable|string',
            'risk_anesthetic' => 'nullable|string',
            'professional_user_id' => 'required|string|size:36',
            'external_provider_name' => 'nullable|string|max:150',
            'status' => 'required|string|max:50',
        ]);

        $clinicalRecord = ClinicalRecord::create($validated);

        return response()->json([
            'message' => 'Historia clínica creada exitosamente.',
            'clinical_record' => $clinicalRecord
        ], 201);
    }

    // Mostrar una historia clínica específica (GET)
    public function show(ClinicalRecord $clinicalRecord)
    {
        return response()->json($clinicalRecord, 200);
    }

    // Actualizar una historia clínica (PUT/PATCH)
    public function update(Request $request, ClinicalRecord $clinicalRecord)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:160',
            'chief_complaint' => 'sometimes|nullable|string',
            'current_illness' => 'sometimes|nullable|string',
            'asa_classification' => 'sometimes|nullable|string|max:20',
            'risk_medical' => 'sometimes|nullable|string',
            'risk_periodontal' => 'sometimes|nullable|string',
            'risk_anesthetic' => 'sometimes|nullable|string',
            'status' => 'sometimes|string|max:50',
        ]);

        $clinicalRecord->update($validated);

        return response()->json([
            'message' => 'Historia clínica actualizada exitosamente.',
            'clinical_record' => $clinicalRecord
        ], 200);
    }

    // Método personalizado para cerrar la historia clínica (PATCH / POST)
    public function close(Request $request, ClinicalRecord $clinicalRecord)
    {
        $clinicalRecord->update([
            'status' => 'closed',
            'signed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Historia clínica cerrada y firmada exitosamente.',
            'clinical_record' => $clinicalRecord
        ], 200);
    }
}