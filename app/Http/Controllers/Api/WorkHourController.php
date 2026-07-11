<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkHour;
use Illuminate\Http\Request;

class WorkHourController extends Controller
{
    // Listar todos los horarios de atención (GET)
    public function index()
    {
        $workHours = WorkHour::orderBy('weekday', 'asc')->get();

        return response()->json($workHours, 200);
    }

    // Actualizar un horario específico por ID o día (PATCH)
    public function update(Request $request, WorkHour $workHour)
    {
        $validated = $request->validate([
            'morning_start_time' => 'nullable|date_format:H:i:s',
            'morning_end_time' => 'nullable|date_format:H:i:s',
            'evening_start_time' => 'nullable|date_format:H:i:s',
            'evening_end_time' => 'nullable|date_format:H:i:s',
            'is_active' => 'boolean',
        ]);

        $workHour->update($validated);

        return response()->json([
            'message' => 'Horario actualizado exitosamente.',
            'work_hour' => $workHour
        ], 200);
    }
}