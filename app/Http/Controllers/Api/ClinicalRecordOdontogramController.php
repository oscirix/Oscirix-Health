<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecordOdontogramItem;
use Illuminate\Http\Request;

class ClinicalRecordOdontogramController extends Controller
{
    public function index($clinicalRecordId)
    {
        $items = ClinicalRecordOdontogramItem::where('clinical_record_id', $clinicalRecordId)->get();

        return response()->json([
            'odontogram_items' => $items
        ], 200);
    }

    public function store(Request $request, $clinicalRecordId)
    {
        $validated = $request->validate([
            'tooth_number' => 'required|string|max:5',
            'dentition_type' => 'required|string|in:permanent,temporary',
            'surface' => 'required|string|in:O,M,D,V,L,B,P,full,none',
            'finding_type' => 'required|string|in:caries,restoration,endodontics,crown,bridge,extraction,implant,fracture,sealant,other',
            'finding_status' => 'required|string|in:active,treated,planned,resolved',
            'symbol_color' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
        ]);

        $item = ClinicalRecordOdontogramItem::create([
            'clinical_record_id' => $clinicalRecordId,
            'tooth_number' => $validated['tooth_number'],
            'dentition_type' => $validated['dentition_type'],
            'surface' => $validated['surface'],
            'finding_type' => $validated['finding_type'],
            'finding_status' => $validated['finding_status'],
            'symbol_color' => $validated['symbol_color'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => $request->user() ? $request->user()->id : null,
        ]);

        return response()->json([
            'message' => 'Ítem de odontograma registrado exitosamente.',
            'odontogram_item' => $item
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $item = ClinicalRecordOdontogramItem::findOrFail($id);

        $validated = $request->validate([
            'tooth_number' => 'sometimes|string|max:5',
            'dentition_type' => 'sometimes|string|in:permanent,temporary',
            'surface' => 'sometimes|string|in:O,M,D,V,L,B,P,full,none',
            'finding_type' => 'sometimes|string|in:caries,restoration,endodontics,crown,bridge,extraction,implant,fracture,sealant,other',
            'finding_status' => 'sometimes|string|in:active,treated,planned,resolved',
            'symbol_color' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Ítem de odontograma actualizado exitosamente.',
            'odontogram_item' => $item
        ], 200);
    }

    public function destroy($id)
    {
        $item = ClinicalRecordOdontogramItem::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'Ítem de odontograma eliminado exitosamente.'
        ], 200);
    }
}