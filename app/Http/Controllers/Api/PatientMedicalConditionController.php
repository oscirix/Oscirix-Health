<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PatientMedicalCondition;
use Illuminate\Http\Request;

class PatientMedicalConditionController extends Controller
{
    // Listar todas las condiciones médicas de un paciente
    public function index($patientId)
    {
        $conditions = PatientMedicalCondition::where('patient_id', $patientId)->get();

        return response()->json([
            'patient_medical_conditions' => $conditions
        ], 200);
    }

    // Registrar una nueva condición médica para un paciente
    public function store(Request $request, $patientId)
    {
        $request->validate([
            'condition_name' => 'required|string|max:160',
            'status'         => 'required|string|in:active,controlled,inactive,unknown',
            'severity'       => 'required|string|in:low,moderate,high',
            'notes'          => 'nullable|string',
        ]);

        $condition = PatientMedicalCondition::create([
            'patient_id'     => $patientId,
            'condition_name' => $request->input('condition_name'),
            'status'         => $request->input('status', 'unknown'),
            'severity'       => $request->input('severity', 'low'),
            'notes'          => $request->input('notes'),
        ]);

        return response()->json([
            'message'                  => 'Condición médica registrada exitosamente.',
            'patient_medical_condition'=> $condition
        ], 201);
    }

    // Eliminar una condición médica específica
    public function destroy($id)
    {
        $condition = PatientMedicalCondition::findOrFail($id);
        $condition->delete();

        return response()->json([
            'message' => 'Condición médica eliminada exitosamente.'
        ], 200);
    }
}