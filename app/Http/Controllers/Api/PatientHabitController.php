<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PatientHabit;
use Illuminate\Http\Request;

class PatientHabitController extends Controller
{
    // Listar todos los hábitos de un paciente
    public function index($patientId)
    {
        $habits = PatientHabit::where('patient_id', $patientId)->get();

        return response()->json([
            'patient_habits' => $habits
        ], 200);
    }

    // Registrar un nuevo hábito para un paciente
    public function store(Request $request, $patientId)
    {
        $request->validate([
            'habit_type' => 'required|string|in:smoking,alcohol,bruxism,brushing,other',
            'value'      => 'nullable|string|max:160',
            'notes'      => 'nullable|string',
        ]);

        $habit = PatientHabit::create([
            'patient_id' => $patientId,
            'habit_type' => $request->input('habit_type'),
            'value'      => $request->input('value'),
            'notes'      => $request->input('notes'),
        ]);

        return response()->json([
            'message'       => 'Hábito registrado exitosamente.',
            'patient_habit' => $habit
        ], 201);
    }

    // Eliminar un hábito específico
    public function destroy($id)
    {
        $habit = PatientHabit::findOrFail($id);
        $habit->delete();

        return response()->json([
            'message' => 'Hábito eliminado exitosamente.'
        ], 200);
    }
}