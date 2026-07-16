<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamRequest;
use App\Models\ExamFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ExamRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamRequest::query();

        if ($request->has('clinical_record_id')) {
            $query->where('clinical_record_id', $request->clinical_record_id);
        }

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $examRequests = $query->with('files')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'exam_requests' => $examRequests
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clinical_record_id' => 'required|string|size:36',
            'patient_id' => 'required|string|size:36',
            'exam_category' => 'required|string',
            'exam_type' => 'required|string|max:160',
            'tooth_number' => 'nullable|string|max:5',
            'request_notes' => 'nullable|string',
            'requested_at' => 'required|date',
        ]);

        $examRequest = ExamRequest::create([
            'id' => (string) Str::uuid(),
            'clinical_record_id' => $validated['clinical_record_id'],
            'patient_id' => $validated['patient_id'],
            'exam_category' => $validated['exam_category'],
            'exam_type' => $validated['exam_type'],
            'tooth_number' => $validated['tooth_number'] ?? null,
            'request_notes' => $validated['request_notes'] ?? null,
            'status' => 'requested',
            'requested_by' => $request->user() ? $request->user()->id : null,
            'requested_at' => $validated['requested_at'],
        ]);

        return response()->json([
            'message' => 'Solicitud de examen creada exitosamente.',
            'exam_request' => $examRequest
        ], 201);
    }

    public function show($id)
    {
        $examRequest = ExamRequest::with('files')->findOrFail($id);

        return response()->json([
            'exam_request' => $examRequest
        ], 200);
    }

    public function updateResult(Request $request, $id)
    {
        $examRequest = ExamRequest::findOrFail($id);

        $validated = $request->validate([
            'result_notes' => 'nullable|string',
            'observations' => 'nullable|string',
            'status' => 'required|string|in:requested,pending,completed,cancelled',
            'completed_at' => 'nullable|date',
        ]);

        $examRequest->update([
            'result_notes' => $validated['result_notes'] ?? $examRequest->result_notes,
            'observations' => $validated['observations'] ?? $examRequest->observations,
            'status' => $validated['status'],
            'completed_at' => $validated['completed_at'] ?? ($validated['status'] === 'completed' ? now() : $examRequest->completed_at),
        ]);

        return response()->json([
            'message' => 'Resultado de examen actualizado exitosamente.',
            'exam_request' => $examRequest
        ], 200);
    }

    public function storeFile(Request $request, $id)
    {
        $examRequest = ExamRequest::findOrFail($id);

        $request->validate([
            'file' => 'required|file|max:10240',
            'file_role' => 'required|string|in:request,result,image,report,attachment',
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('exam-files', 'public');

        $examFile = ExamFile::create([
            'id' => (string) Str::uuid(),
            'exam_request_id' => $examRequest->id,
            'file_path' => $path,
            'file_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
            'file_size_kb' => round($uploadedFile->getSize() / 1024),
            'file_role' => $request->input('file_role'),
            'uploaded_by' => $request->user() ? $request->user()->id : null,
        ]);

        return response()->json([
            'message' => 'Archivo adjuntado a la solicitud exitosamente.',
            'exam_file' => $examFile
        ], 201);
    }

    public function downloadFile($id)
    {
        $examFile = ExamFile::findOrFail($id);

        $fullPath = storage_path('app/public/' . $examFile->file_path);

        if (!file_exists($fullPath)) {
            return response()->json(['message' => 'El archivo físico no existe en el servidor.'], 404);
        }

        return response()->download($fullPath, $examFile->file_name);
    }
}