<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExamFileController extends Controller
{
    public function index($examRequestId)
    {
        $files = ExamFile::where('exam_request_id', $examRequestId)->get();

        return response()->json([
            'exam_files' => $files
        ], 200);
    }

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

        // Guardar el archivo en storage/app/public/exam-files
        $path = $uploadedFile->store('exam-files', 'public');

        $examFile = ExamFile::create([
            'id' => (string) Str::uuid(),
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

    /*public function destroy($id)
    {
        $examFile = ExamFile::findOrFail($id);
        
        // Opcional: eliminar el archivo físico del storage si existe
        if (\Storage::disk('public')->exists($examFile->file_path)) {
            \Storage::disk('public')->delete($examFile->file_path);
        }

        $examFile->delete();

        return response()->json([
            'message' => 'Archivo de examen eliminado exitosamente.'
        ], 200);
    }*/
}