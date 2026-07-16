<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecordEvolution;
use Illuminate\Http\Request;

class ClinicalRecordEvolutionController extends Controller
{
    public function index($clinicalRecordId)
    {
        $evolutions = ClinicalRecordEvolution::where('clinical_record_id', $clinicalRecordId)
            ->orderBy('performed_at', 'desc')
            ->get();

        return response()->json([
            'evolutions' => $evolutions
        ], 200);
    }

    public function store(Request $request, $clinicalRecordId)
    {
        $validated = $request->validate([
            'evolution_type' => 'required|string|in:consultation,procedure,follow_up,emergency,other',
            'title' => 'required|string|max:180',
            'notes' => 'nullable|string',
            'indications' => 'nullable|string',
            'performed_at' => 'required|date',
            'professional_user_id' => 'nullable|string|max:36',
            'signature_url' => 'nullable|string|max:500',
        ]);

        $evolution = ClinicalRecordEvolution::create([
            'clinical_record_id' => $clinicalRecordId,
            'evolution_type' => $validated['evolution_type'],
            'title' => $validated['title'],
            'notes' => $validated['notes'] ?? null,
            'indications' => $validated['indications'] ?? null,
            'performed_at' => $validated['performed_at'],
            'professional_user_id' => $validated['professional_user_id'] ?? ($request->user() ? $request->user()->id : null),
            'signature_url' => $validated['signature_url'] ?? null,
        ]);

        return response()->json([
            'message' => 'Evolución clínica registrada exitosamente.',
            'evolution' => $evolution
        ], 201);
    }
}