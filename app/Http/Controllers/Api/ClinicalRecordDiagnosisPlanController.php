<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecordDiagnosis;
use Illuminate\Http\Request;

class ClinicalRecordDiagnosisPlanController extends Controller
{
    public function show($clinicalRecordId)
    {
        $diagnoses = ClinicalRecordDiagnosis::where('clinical_record_id', $clinicalRecordId)->get();

        return response()->json([
            'diagnoses' => $diagnoses
        ], 200);
    }

    public function storeDiagnosis(Request $request, $clinicalRecordId)
    {
        $validated = $request->validate([
            'tooth_number' => 'nullable|string|max:5',
            'area_label' => 'nullable|string|max:160',
            'diagnosis_text' => 'required|string',
            'icd10_code' => 'nullable|string|max:20',
            'diagnosis_type' => 'required|string|in:presumptive,definitive',
            'is_primary' => 'boolean',
            'diagnosed_at' => 'required|date',
        ]);

        $diagnosis = ClinicalRecordDiagnosis::create([
            'clinical_record_id' => $clinicalRecordId,
            'tooth_number' => $validated['tooth_number'] ?? null,
            'area_label' => $validated['area_label'] ?? null,
            'diagnosis_text' => $validated['diagnosis_text'],
            'icd10_code' => $validated['icd10_code'] ?? null,
            'diagnosis_type' => $validated['diagnosis_type'],
            'is_primary' => $validated['is_primary'] ?? false,
            'diagnosed_at' => $validated['diagnosed_at'],
        ]);

        return response()->json([
            'message' => 'Diagnóstico registrado exitosamente.',
            'diagnosis' => $diagnosis
        ], 201);
    }

    public function storeTreatmentPlan(Request $request, $clinicalRecordId)
    {
        return response()->json(['message' => 'ClinicalRecordDiagnosisPlanController@storeTreatmentPlan pending implementation']);
    }

    public function updateStep(Request $request, $stepId)
    {
        return response()->json(['message' => 'ClinicalRecordDiagnosisPlanController@updateStep pending implementation']);
    }
}