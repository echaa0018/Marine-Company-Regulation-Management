<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\PdfWatermarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentPreviewController extends Controller
{
    public function previewWatermarked(Request $request, $documentId, $filename)
    {
        try {
            // Load the document
            $document = $this->loadDocument($documentId);
            
            if (!$document) {
                abort(404, 'Document not found');
            }

            // Check if file exists on S3/MinIO
            if (!Storage::disk('s3')->exists($document['file_path'])) {
                abort(404, 'File not found');
            }

            // Get the file content from S3/MinIO
            $fileContent = Storage::disk('s3')->get($document['file_path']);
            
            // Check if it's a PDF file
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (strtolower($extension) === 'pdf') {
                // Apply watermark to PDF
                $watermarkService = new PdfWatermarkService();
                $watermarkedContent = $watermarkService->addWatermark($fileContent);
                
                return response($watermarkedContent)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            } else {
                // For non-PDF files, return original content
                $mimeType = $this->getMimeType($extension);
                
                return response($fileContent)
                    ->header('Content-Type', $mimeType)
                    ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            }
            
        } catch (\Exception $e) {
            logger('Error serving watermarked preview: ' . $e->getMessage());
            abort(500, 'Error loading document');
        }
    }

    private function loadDocument($documentId)
    {
        // Try to load from database first
        $dbDocument = Document::find($documentId);
        
        if ($dbDocument) {
            return [
                'id' => $dbDocument->id,
                'number' => $dbDocument->number,
                'title' => $dbDocument->title,
                'type' => $dbDocument->type,
                'published_date' => $dbDocument->published_date->format('Y-m-d'),
                'effective_until' => $dbDocument->effective_until->format('Y-m-d'),
                'status' => $dbDocument->status,
                'confidentiality' => $dbDocument->confidentiality,
                'file' => $dbDocument->file_name,
                'file_path' => $dbDocument->file_path,
            ];
        }

        // If not in database, check session (fallback for session-based documents)
        $allDocuments = session('all_documents', []);
        
        foreach ($allDocuments as $doc) {
            if ($doc['id'] == $documentId) {
                return $doc;
            }
        }

        return null;
    }

    private function getMimeType($extension)
    {
        return match(strtolower($extension)) {
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'txt' => 'text/plain',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => 'application/octet-stream'
        };
    }
}
