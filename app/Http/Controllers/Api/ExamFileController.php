<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExamFileController extends Controller
{
    // Método para subir el archivo (si prefieres tenerlo aquí en lugar del ExamRequestController)
    public function store(Request $request, $examRequestId)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Máximo 10MB
            'file_role' => 'required|string|in:request,result,image,report,attachment',
        ]);

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();
        $mimeType = $uploadedFile->getMimeType();
        $sizeKb = round($uploadedFile->getSize() / 1024);

        $path = $uploadedFile->store('exam-files', 'public');

        $examFile = ExamFile::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'exam_request_id' => $examRequestId,
            'file_path' => $path,
            'file_name' => $originalName,
            'mime_type' => $mimeType,
            'file_size_kb' => $sizeKb,
            'file_role' => $request->input('file_role'),
            'uploaded_by' => $request->user() ? $request->user()->id : null,
        ]);

        return response()->json([
            'message' => 'Archivo de examen subido exitosamente.',
            'exam_file' => $examFile
        ], 201);
    }

    // Método para descargar el archivo usando su ID específico
    public function download($id)
    {
        $examFile = ExamFile::findOrFail($id);

        $fullPath = storage_path('app/public/' . $examFile->file_path);

        if (!file_exists($fullPath)) {
            return response()->json(['message' => 'El archivo físico no existe en el servidor.'], 404);
        }

        return response()->download($fullPath, $examFile->file_name);
    }

    // Método para eliminar un archivo si ya no se necesita
    public function destroy($id)
    {
        $examFile = ExamFile::findOrFail($id);

        // Borrar el archivo físico del storage si existe
        if (Storage::disk('public')->exists($examFile->file_path)) {
            Storage::disk('public')->delete($examFile->file_path);
        }

        // Borrar el registro de la base de datos
        $examFile->delete();

        return response()->json([
            'message' => 'Archivo eliminado exitosamente.'
        ], 200);
    }
}