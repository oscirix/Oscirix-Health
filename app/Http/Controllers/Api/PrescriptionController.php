<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PrescriptionController extends Controller
{
    // Listar recetas de un paciente
    public function index($patientId)
    {
        $prescriptions = Prescription::where('patient_id', $patientId)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'prescriptions' => $prescriptions
        ], 200);
    }

    // Crear una nueva receta
    public function store(Request $request, $patientId)
    {
        $request->validate([
            'clinical_record_id' => 'nullable|string|max:36',
            'indications'        => 'nullable|string',
            'status'             => 'required|string|in:draft,active,suspended,completed',
            'pdf_path'           => 'nullable|string|max:500',
        ]);

        $prescription = Prescription::create([
            'clinical_record_id' => $request->input('clinical_record_id'),
            'patient_id'         => $patientId,
            'prescribed_by'      => $request->user() ? $request->user()->id : null,
            'indications'        => $request->input('indications'),
            'status'             => $request->input('status', 'draft'),
            'prescribed_at'      => Carbon::now(),
            'pdf_path'           => $request->input('pdf_path'),
        ]);

        return response()->json([
            'message'      => 'Receta creada exitosamente.',
            'prescription' => $prescription
        ], 201);
    }

    // Ver detalle de una receta
    public function show($id)
    {
        $prescription = Prescription::findOrFail($id);

        return response()->json([
            'prescription' => $prescription
        ], 200);
    }

    // Actualizar el estado de la receta
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:draft,active,suspended,completed',
        ]);

        $prescription = Prescription::findOrFail($id);
        $prescription->update([
            'status' => $request->input('status')
        ]);

        return response()->json([
            'message'      => 'Estado de la receta actualizado exitosamente.',
            'prescription' => $prescription
        ], 200);
    }
}