<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecordDentalFinding;
use Illuminate\Http\Request;

class ClinicalRecordDentalFindingController extends Controller
{
    public function show($clinicalRecordId)
    {
        $findings = ClinicalRecordDentalFinding::where('clinical_record_id', $clinicalRecordId)->get();

        return response()->json($findings, 200);
    }

    public function upsert(Request $request, $clinicalRecordId)
    {
        $validated = $request->validate([
            'findings' => 'required|array',
            'findings.*.tooth_number' => 'nullable|string|max:5',
            'findings.*.area_label' => 'nullable|string|max:160',
            'findings.*.finding_type' => 'required|string',
            'findings.*.finding_status' => 'required|string|in:active,treated,planned,resolved',
            'findings.*.severity' => 'required|string|in:none,mild,moderate,severe',
            'findings.*.notes' => 'nullable|string',
        ]);

        $updatedFindings = [];
        $userId = $request->user() ? $request->user()->id : null;

        foreach ($validated['findings'] as $item) {
            $finding = ClinicalRecordDentalFinding::updateOrCreate(
                [
                    'clinical_record_id' => $clinicalRecordId,
                    'tooth_number' => $item['tooth_number'] ?? null,
                    'finding_type' => $item['finding_type'],
                ],
                [
                    'area_label' => $item['area_label'] ?? null,
                    'finding_status' => $item['finding_status'],
                    'severity' => $item['severity'],
                    'notes' => $item['notes'] ?? null,
                    'created_by' => $userId,
                ]
            );

            $updatedFindings[] = $finding;
        }

        return response()->json([
            'message' => 'Hallazgos dentales guardados/actualizados exitosamente.',
            'dental_findings' => $updatedFindings
        ], 200);
    }
}