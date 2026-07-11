<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TreatmentMedia;
use Illuminate\Http\Request;

class TreatmentMediaController extends Controller
{
    // Obtener los archivos multimedia, filtrando opcionalmente por treatment_id (GET)
    public function index(Request $request)
    {
        $query = TreatmentMedia::query();

        if ($request->has('treatment_id')) {
            $query->where('treatment_id', $request->treatment_id);
        }

        $media = $query->orderBy('sort_order', 'asc')->get();

        return response()->json($media, 200);
    }

    // Mostrar un archivo multimedia específico por su ID (GET)
    public function show(TreatmentMedia $treatmentMedia)
    {
        return response()->json($treatmentMedia, 200);
    }
}