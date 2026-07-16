<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PatientAlert;
use Illuminate\Http\Request;

class PatientAlertController extends Controller
{
    // Listar todas las alertas de un paciente específico
    public function index($patientId)
    {
        $alerts = PatientAlert::where('patient_id', $patientId)->get();

        return response()->json([
            'patient_alerts' => $alerts
        ], 200);
    }

    // Crear una nueva alerta para un paciente
    public function store(Request $request, $patientId)
    {
        $request->validate([
            'alert_type' => 'required|string|in:allergy,medical_condition,habit,risk',
            'severity'   => 'required|string|in:low,moderate,high,critical',
            'title'      => 'required|string|max:160',
            'description'=> 'nullable|string',
            'source_table' => 'nullable|string|max:80',
            'source_id'  => 'nullable|string|max:36',
            'is_active'  => 'boolean',
        ]);

        $alert = PatientAlert::create([
            'patient_id'   => $patientId,
            'alert_type'   => $request->input('alert_type'),
            'severity'     => $request->input('severity'),
            'title'        => $request->input('title'),
            'description'  => $request->input('description'),
            'source_table' => $request->input('source_table'),
            'source_id'    => $request->input('source_id'),
            'is_active'    => $request->input('is_active', true),
            'created_by'   => $request->user() ? $request->user()->id : null,
        ]);

        return response()->json([
            'message'      => 'Alerta de paciente creada exitosamente.',
            'patient_alert' => $alert
        ], 201);
    }

    // Desactivar o eliminar una alerta específica
    public function destroy($id)
    {
        $alert = PatientAlert::findOrFail($id);
        $alert->delete();

        return response()->json([
            'message' => 'Alerta eliminada exitosamente.'
        ], 200);
    }
}