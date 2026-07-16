<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecord;
use App\Models\ClinicalRecordAnamnesis;
use Illuminate\Http\Request;

class ClinicalRecordAnamnesisController extends Controller
{
    // Obtener todas las secciones de anamnesis de una historia clínica (GET)
    public function show($clinicalRecordId)
    {
        $anamnesis = ClinicalRecordAnamnesis::where('clinical_record_id', $clinicalRecordId)->get();

        return response()->json($anamnesis, 200);
    }

    // Crear o actualizar secciones de anamnesis (PUT / Upsert)
    public function upsert(Request $request, $clinicalRecordId)
    {
        // Validamos que se envíe un array de secciones o una sección individual
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.section' => 'required|string|in:consultation_reason,current_illness,medical_history,dental_history,family_history,habits,notes',
            'sections.*.content' => 'required|string',
        ]);

        $updatedRecords = [];

        foreach ($validated['sections'] as $item) {
            $record = ClinicalRecordAnamnesis::updateOrCreate(
                [
                    'clinical_record_id' => $clinicalRecordId,
                    'section' => $item['section'],
                ],
                [
                    'content' => $item['content'],
                ]
            );

            $updatedRecords[] = $record;
        }

        return response()->json([
            'message' => 'Anamnesis guardada/actualizada exitosamente.',
            'anamnesis' => $updatedRecords
        ], 200);
    }
}