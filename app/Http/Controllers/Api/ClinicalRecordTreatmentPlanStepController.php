<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecordTreatmentPlanStep;
use Illuminate\Http\Request;

class ClinicalRecordTreatmentPlanStepController extends Controller
{
    public function index($treatmentPlanId)
    {
        $steps = ClinicalRecordTreatmentPlanStep::where('treatment_plan_id', $treatmentPlanId)
            ->orderBy('step_order', 'asc')
            ->get();

        return response()->json([
            'treatment_plan_steps' => $steps
        ], 200);
    }

    public function store(Request $request, $treatmentPlanId)
    {
        $validated = $request->validate([
            'step_order' => 'required|integer',
            'title' => 'required|string|max:180',
            'description' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'status' => 'required|string|in:pending,in_progress,completed,skipped',
        ]);

        $step = ClinicalRecordTreatmentPlanStep::create([
            'treatment_plan_id' => $treatmentPlanId,
            'step_order' => $validated['step_order'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'completed_at' => $validated['completed_at'] ?? null,
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Paso del plan de tratamiento creado exitosamente.',
            'treatment_plan_step' => $step
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $step = ClinicalRecordTreatmentPlanStep::findOrFail($id);

        $validated = $request->validate([
            'step_order' => 'sometimes|integer',
            'title' => 'sometimes|string|max:180',
            'description' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'status' => 'sometimes|string|in:pending,in_progress,completed,skipped',
        ]);

        $step->update($validated);

        return response()->json([
            'message' => 'Paso del plan de tratamiento actualizado exitosamente.',
            'treatment_plan_step' => $step
        ], 200);
    }

    public function destroy($id)
    {
        $step = ClinicalRecordTreatmentPlanStep::findOrFail($id);
        $step->delete();

        return response()->json([
            'message' => 'Paso del plan de tratamiento eliminado exitosamente.'
        ], 200);
    }
}