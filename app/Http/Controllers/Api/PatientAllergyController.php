<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PatientAllergy;
use Illuminate\Http\Request;

class PatientAllergyController extends Controller
{
    // Listar todas las alergias de un paciente
    public function index($patientId)
    {
        $allergies = PatientAllergy::where('patient_id', $patientId)->get();

        return response()->json([
            'patient_allergies' => $allergies
        ], 200);
    }

    // Registrar una nueva alergia para un paciente
    public function store(Request $request, $patientId)
    {
        $request->validate([
            'allergen'     => 'required|string|max:160',
            'reaction'     => 'nullable|string|max:255',
            'severity'     => 'required|string|in:mild,moderate,severe,critical',
            'is_confirmed' => 'boolean',
            'notes'        => 'nullable|string',
        ]);

        $allergy = PatientAllergy::create([
            'patient_id'   => $patientId,
            'allergen'     => $request->input('allergen'),
            'reaction'     => $request->input('reaction'),
            'severity'     => $request->input('severity', 'mild'),
            'is_confirmed' => $request->input('is_confirmed', false),
            'notes'        => $request->input('notes'),
        ]);

        return response()->json([
            'message'         => 'Alergia registrada exitosamente.',
            'patient_allergy' => $allergy
        ], 201);
    }

    // Eliminar una alergia específica
    public function destroy($id)
    {
        $allergy = PatientAllergy::findOrFail($id);
        $allergy->delete();

        return response()->json([
            'message' => 'Alergia eliminada exitosamente.'
        ], 200);
    }
}