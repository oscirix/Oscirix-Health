<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecordPeriodontalIndex;
use Illuminate\Http\Request;

class ClinicalRecordPeriodontalIndexController extends Controller
{
    public function show($clinicalRecordId)
    {
        $indices = ClinicalRecordPeriodontalIndex::where('clinical_record_id', $clinicalRecordId)->get();

        return response()->json([
            'periodontal_indices' => $indices
        ], 200);
    }

    public function storeOrUpdate(Request $request, $clinicalRecordId)
    {
        $validated = $request->validate([
            'plaque_index_percent' => 'nullable|numeric|between:0,100',
            'gingival_bleeding_percent' => 'nullable|numeric|between:0,100',
            'max_probe_depth_mm' => 'nullable|numeric',
            'insertion_level_mm' => 'nullable|numeric',
            'cpod_total' => 'nullable|integer',
            'active_caries_count' => 'nullable|integer',
        ]);

        $indices = ClinicalRecordPeriodontalIndex::updateOrCreate(
            ['clinical_record_id' => $clinicalRecordId],
            [
                'plaque_index_percent' => $validated['plaque_index_percent'] ?? null,
                'gingival_bleeding_percent' => $validated['gingival_bleeding_percent'] ?? null,
                'max_probe_depth_mm' => $validated['max_probe_depth_mm'] ?? null,
                'insertion_level_mm' => $validated['insertion_level_mm'] ?? null,
                'cpod_total' => $validated['cpod_total'] ?? null,
                'active_caries_count' => $validated['active_caries_count'] ?? null,
            ]
        );

        return response()->json([
            'message' => 'Índices periodontales guardados exitosamente.',
            'periodontal_indices' => $indices
        ], 200);
    }
}