<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // Listar citas de la clínica del usuario
    public function index(Request $request)
    {
        $clinicId = $request->user()->clinic_id;

        // Como las citas relacionan pacientes, podemos filtrar opcionalmente por clinic_id o a través del paciente
        $appointments = Appointment::where('clinic_id', $clinicId)->paginate(15);

        return response()->json($appointments, 200);
    }

    // Crear una cita nueva
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|string|size:36',
            'treatment_id' => 'nullable|string|size:36',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'status' => 'required|in:confirmed,cancelled,completed,no_show',
            'source' => 'required|in:web,whatsapp,dashboard',
            'appointment_note' => 'nullable|string',
            'cancel_reason' => 'nullable|string',
            'cancelled_at' => 'nullable|date',
        ]);

        $validated['clinic_id'] = $request->user()->clinic_id;
        $validated['created_by_user_id'] = $request->user()->id;

        $appointment = new Appointment();
        $appointment->fill($validated);
        $appointment->save();

        return response()->json([
            'message' => 'Cita creada exitosamente.',
            'appointment' => $appointment
        ], 201);
    }

    // Actualizar una cita existente
    public function update(Request $request, Appointment $appointment)
    {
        if ($appointment->clinic_id !== $request->user()->clinic_id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $validated = $request->validate([
            'patient_id' => 'sometimes|string|size:36',
            'treatment_id' => 'nullable|string|size:36',
            'start_at' => 'sometimes|date',
            'end_at' => 'sometimes|date',
            'status' => 'sometimes|in:confirmed,cancelled,completed,no_show',
            'source' => 'sometimes|in:web,whatsapp,dashboard',
            'appointment_note' => 'nullable|string',
            'cancel_reason' => 'nullable|string',
            'cancelled_at' => 'nullable|date',
        ]);

        $appointment->update($validated);

        return response()->json([
            'message' => 'Cita actualizada exitosamente.',
            'appointment' => $appointment
        ], 200);
    }
}