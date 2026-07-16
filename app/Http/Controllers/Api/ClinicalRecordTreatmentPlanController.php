<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecordTreatmentPlan;
use Illuminate\Http\Request;

class ClinicalRecordTreatmentPlanController extends Controller
{
    public function index($clinicalRecordId)
    {
        $plans = ClinicalRecordTreatmentPlan::where('clinical_record_id', $clinicalRecordId)
            ->with('steps') // Por si luego relacionamos los pasos
            ->get();

        return response()->json([
            'treatment_plans' => $plans
        ], 200);
    }

    public function store(Request $request, $clinicalRecordId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'status' => 'required|string|in:planned,in_progress,completed,suspended',
            'goal_notes' => 'nullable|string',
        ]);

        $plan = ClinicalRecordTreatmentPlan::create([
            'clinical_record_id' => $clinicalRecordId,
            'title' => $validated['title'],
            'status' => $validated['status'],
            'goal_notes' => $validated['goal_notes'] ?? null,
            'created_by' => $request->user() ? $request->user()->id : null,
        ]);

        return response()->json([
            'message' => 'Plan de tratamiento creado exitosamente.',
            'treatment_plan' => $plan
        ], 201);
    }

    public function destroy($id)
    {
        $plan = ClinicalRecordTreatmentPlan::findOrFail($id);
        $plan->delete();

        return response()->json([
            'message' => 'Plan de tratamiento eliminado exitosamente.'
        ], 200);
    }
}