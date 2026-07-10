<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    // Listar testimonios (filtrando los activos para la landing)
    public function index(Request $request)
    {
        $testimonials = Testimonial::where('is_active', true)->get();

        return response()->json($testimonials, 200);
    }

    // Crear un nuevo testimonio
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:150',
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar_url' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $testimonial = new Testimonial();
        $testimonial->fill($validated);
        $testimonial->save();

        return response()->json([
            'message' => 'Testimonio creado exitosamente.',
            'testimonial' => $testimonial
        ], 201);
    }

    // Mostrar un testimonio específico
    public function show(Testimonial $testimonial)
    {
        return response()->json($testimonial, 200);
    }

    // Actualizar un testimonio existente
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'patient_name' => 'sometimes|string|max:150',
            'comment' => 'sometimes|string',
            'rating' => 'sometimes|integer|min:1|max:5',
            'avatar_url' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $testimonial->update($validated);

        return response()->json([
            'message' => 'Testimonio actualizado exitosamente.',
            'testimonial' => $testimonial
        ], 200);
    }
}