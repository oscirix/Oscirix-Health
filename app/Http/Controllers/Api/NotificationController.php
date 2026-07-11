<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Listar las notificaciones (GET), opcionalmente filtradas por usuario autenticado o ID
    public function index(Request $request)
    {
        $query = Notification::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $notifications = $query->orderBy('created_at', 'desc')->get();

        return response()->json($notifications, 200);
    }

    // Crear una nueva notificación (POST)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|size:36',
            'patient_id' => 'nullable|string|size:36',
            'appointment_id' => 'nullable|string|size:36',
            'lead_id' => 'nullable|string|size:36',
            'title' => 'required|string|max:160',
            'description' => 'required|string',
            'status' => 'required|in:sent,read',
        ]);

        $notification = Notification::create($validated);

        return response()->json([
            'message' => 'Notificación creada exitosamente.',
            'notification' => $notification
        ], 201);
    }

    // Mostrar una notificación específica (GET)
    public function show(Notification $notification)
    {
        return response()->json($notification, 200);
    }

    // Actualizar el estado de lectura (PATCH / PUT)
    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:sent,read',
            'title' => 'sometimes|string|max:160',
            'description' => 'sometimes|string',
        ]);

        $notification->update($validated);

        return response()->json([
            'message' => 'Notificación actualizada exitosamente.',
            'notification' => $notification
        ], 200);
    }

    // Eliminar notificación (DELETE)
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->json([
            'message' => 'Notificación eliminada exitosamente.'
        ], 200);
    }
}