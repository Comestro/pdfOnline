<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function preview($id)
    {
        $document = Document::findOrFail($id);

        $filePath = $document->file_path;

        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        $mimeType = Storage::disk('private')->mimeType($filePath);
        $absolutePath = storage_path('app/private/' . $filePath);

        return response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        ]);
    }
}
