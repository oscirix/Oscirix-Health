<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecordClinicalExam;
use Illuminate\Http\Request;

class ClinicalRecordExamController extends Controller
{
    // Obtener todos los exámenes clínicos de una historia
    public function show($clinicalRecordId)
    {
        $exams = ClinicalRecordClinicalExam::where('clinical_record_id', $clinicalRecordId)->get();

        return response()->json($exams, 200);
    }

    // Crear o actualizar múltiples ítems de examen clínico (PUT / Upsert)
    public function upsert(Request $request, $clinicalRecordId)
    {
        $validated = $request->validate([
            'exams' => 'required|array',
            'exams.*.exam_area' => 'required|string|in:extraoral,intraoral,periodontal,dental',
            'exams.*.item_name' => 'required|string|max:160',
            'exams.*.finding' => 'nullable|string|max:255',
            'exams.*.status' => 'required|string|in:not_evaluated,normal,altered,positive,negative',
            'exams.*.severity' => 'required|string|in:none,mild,moderate,severe',
            'exams.*.notes' => 'nullable|string',
        ]);

        $updatedExams = [];

        foreach ($validated['exams'] as $item) {
            $exam = ClinicalRecordClinicalExam::updateOrCreate(
                [
                    'clinical_record_id' => $clinicalRecordId,
                    'exam_area' => $item['exam_area'],
                    'item_name' => $item['item_name'],
                ],
                [
                    'finding' => $item['finding'] ?? null,
                    'status' => $item['status'],
                    'severity' => $item['severity'],
                    'notes' => $item['notes'] ?? null,
                ]
            );

            $updatedExams[] = $exam;
        }

        return response()->json([
            'message' => 'Exámenes clínicos guardados/actualizados exitosamente.',
            'clinical_exams' => $updatedExams
        ], 200);
    }
}