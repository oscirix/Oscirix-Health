<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    // Listar todos los tratamientos (puedes filtrar por los activos si lo deseas)
    public function index(Request $request)
    {
        $treatments = Treatment::where('is_active', true)->get();

        return response()->json($treatments, 200);
    }

    // Crear un tratamiento nuevo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:140|unique:treatments,slug',
            'short_desc' => 'nullable|string',
            'full_desc' => 'nullable|string',
            'benefits' => 'nullable|string',
            'default_duration_min' => 'required|integer|min:5',
            'main_image_url' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $treatment = new Treatment();
        $treatment->fill($validated);
        $treatment->save();

        return response()->json([
            'message' => 'Tratamiento creado exitosamente.',
            'treatment' => $treatment
        ], 201);
    }

    // Mostrar un tratamiento específico
    public function show(Treatment $treatment)
    {
        return response()->json($treatment, 200);
    }

    // Actualizar un tratamiento
    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:120',
            'slug' => 'sometimes|string|max:140|unique:treatments,slug,' . $treatment->id,
            'short_desc' => 'nullable|string',
            'full_desc' => 'nullable|string',
            'benefits' => 'nullable|string',
            'default_duration_min' => 'sometimes|integer|min:5',
            'main_image_url' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $treatment->update($validated);

        return response()->json([
            'message' => 'Tratamiento actualizado exitosamente.',
            'treatment' => $treatment
        ], 200);
    }
}