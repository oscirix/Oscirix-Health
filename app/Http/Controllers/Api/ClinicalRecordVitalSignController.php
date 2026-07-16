<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecordVitalSign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClinicalRecordVitalSignController extends Controller
{
    public function index($clinicalRecordId)
    {
        $vitalSigns = ClinicalRecordVitalSign::where('clinical_record_id', $clinicalRecordId)
            ->orderBy('measured_at', 'desc')
            ->get();

        return response()->json([
            'vital_signs' => $vitalSigns
        ], 200);
    }

    public function store(Request $request, $clinicalRecordId)
    {
        $validated = $request->validate([
            'blood_pressure' => 'nullable|string|max:20',
            'heart_rate' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'temperature_c' => 'nullable|numeric',
            'oxygen_saturation_percent' => 'nullable|numeric',
            'measured_at' => 'required|date',
        ]);

        $vitalSign = ClinicalRecordVitalSign::create([
            'id' => (string) Str::uuid(),
            'clinical_record_id' => $clinicalRecordId,
            'blood_pressure' => $validated['blood_pressure'] ?? null,
            'heart_rate' => $validated['heart_rate'] ?? null,
            'respiratory_rate' => $validated['respiratory_rate'] ?? null,
            'temperature_c' => $validated['temperature_c'] ?? null,
            'oxygen_saturation_percent' => $validated['oxygen_saturation_percent'] ?? null,
            'measured_at' => $validated['measured_at'],
        ]);

        return response()->json([
            'message' => 'Signos vitales registrados exitosamente.',
            'vital_sign' => $vitalSign
        ], 201);
    }

    public function destroy($id)
    {
        $vitalSign = ClinicalRecordVitalSign::findOrFail($id);
        $vitalSign->delete();

        return response()->json([
            'message' => 'Registro de signos vitales eliminado exitosamente.'
        ], 200);
    }
}