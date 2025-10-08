<?php

namespace App\Livewire\Document;

use App\Models\Document;
use App\Services\PdfWatermarkService;
use Livewire\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('components.layouts.app')]
class DocumentDetail extends Component
{
    use Toast;

    public $documentId;
    public $document;
    public $previousPage;
    public $searchTerm;

    public function mount($id)
    {
        $this->documentId = $id;

        // Get pagination and search state from session or request
        $this->previousPage = request()->get('page', session('documents_current_page', 1));
        $this->searchTerm = request()->get('search', session('documents_search', ''));

        // First try to get from session (for created documents)
        $selectedDocument = session('selected_document');
        if ($selectedDocument && $selectedDocument['id'] == $id) {
            $this->document = $selectedDocument;
            return;
        }

        // If not in session, load from static documents
        $this->loadDocument();
    }

    private function loadDocument()
    {
        // Try to load from database first
        $dbDocument = Document::with(['revokes', 'changes', 'revokedBy', 'changedBy'])
            ->find($this->documentId);

        if ($dbDocument) {
            logger("DocumentDetail: Loading document from database: {$dbDocument->title}");
            // Transform database model to array format compatible with existing code
            $this->document = [
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
                'revokes' => $dbDocument->revokes->pluck('number')->toArray(),
                'changes' => $dbDocument->changes->pluck('number')->toArray(),
                'revoked_by' => $dbDocument->revokedBy->isNotEmpty() ? $dbDocument->revokedBy->first()->number : null,
                'changed_by' => $dbDocument->changedBy->pluck('number')->toArray(),
            ];
            logger("DocumentDetail: Successfully loaded document from database");
            return;
        }

        logger("DocumentDetail: Document not found in database, checking session data");

        // Fallback: Use the component's helper to get the FULL list of documents (for session documents)
        $allDocuments = $this->getAllDocuments();

        // Find the document by ID in the complete list
        foreach ($allDocuments as $doc) {
            if ($doc['id'] == $this->documentId) {
                logger("DocumentDetail: Found document in session data: {$doc['title']}");
                $this->document = $doc;
                return;
            }
        }

        // Document not found
        logger("DocumentDetail: Document with ID {$this->documentId} not found anywhere");
        $this->document = null;
        $this->error('Document not found');
        return redirect()->route('document.catalog');
    }

    /**
     * Helper method to get all documents for lookups
     */
    private function getAllDocuments()
    {
        $documentListComponent = new DocumentList();
        return $documentListComponent->getAllDocuments();
    }

    /**
     * Helper method to find document title by its identifier (number or title)
     */
    public function findDocumentTitle($identifier)
    {
        $allDocuments = $this->getAllDocuments();

        foreach ($allDocuments as $doc) {
            // Check if identifier matches document number or title
            if ($doc['number'] === $identifier || $doc['title'] === $identifier) {
                return $doc['title'];
            }
        }

        // If not found, return the original identifier
        return $identifier;
    }

    /**
     * Helper method to find document by its identifier and return both title and ID for linking
     */
    public function findDocumentForLink($identifier)
    {
        logger("findDocumentForLink called with identifier: '{$identifier}'");

        $allDocuments = $this->getAllDocuments();
        logger("findDocumentForLink: Found " . count($allDocuments) . " total documents");

        foreach ($allDocuments as $doc) {
            // Check if identifier matches document number or title (exact match)
            if ($doc['number'] === $identifier || $doc['title'] === $identifier) {
                logger("findDocumentForLink: Found exact match for '{$identifier}' - Document ID: {$doc['id']}, Title: {$doc['title']}");
                return [
                    'id' => $doc['id'],
                    'title' => $doc['title'],
                    'number' => $doc['number']
                ];
            }
        }

        // If no exact match, try case-insensitive matching
        foreach ($allDocuments as $doc) {
            if (strcasecmp($doc['number'], $identifier) === 0 || strcasecmp($doc['title'], $identifier) === 0) {
                logger("findDocumentForLink: Found case-insensitive match for '{$identifier}' - Document ID: {$doc['id']}, Title: {$doc['title']}");
                return [
                    'id' => $doc['id'],
                    'title' => $doc['title'],
                    'number' => $doc['number']
                ];
            }
        }

        logger("findDocumentForLink: No match found for '{$identifier}' among " . count($allDocuments) . " documents");
        // Log all available document numbers for debugging
        $availableNumbers = array_column($allDocuments, 'number');
        logger("findDocumentForLink: Available document numbers: " . implode(', ', $availableNumbers));

        // If not found, return null
        return null;
    }

    /**
     * Check if the document file exists in storage
     */
    private function fileExists()
    {
        if (!$this->document || !isset($this->document['file_path']) || !$this->document['file_path']) {
            return false;
        }

        try {
            return Storage::disk('s3')->exists($this->document['file_path']);
        } catch (\Exception $e) {
            logger('Error checking file existence: ' . $e->getMessage());
            return false;
        }
    }

    public function downloadFile($filename)
    {
        if (!$this->document || !isset($this->document['file_path']) || !$this->document['file_path']) {
            $this->error("No file associated with this document");
            return;
        }

        try {
            // Check if file exists on S3/MinIO
            if (!$this->fileExists()) {
                $this->error("File not found: {$filename}");
                return;
            }

            // Get the file content from S3/MinIO
            $fileContent = Storage::disk('s3')->get($this->document['file_path']);
            
            // Determine mime type based on file extension
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $mimeType = match(strtolower($extension)) {
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'txt' => 'text/plain',
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                default => 'application/octet-stream'
            };
            
            // Apply watermark to PDF files
            if (strtolower($extension) === 'pdf') {
                $watermarkService = new PdfWatermarkService();
                $fileContent = $watermarkService->addWatermark($fileContent);
            }
            
            // Create a streamed response for download
            return new StreamedResponse(function () use ($fileContent) {
                echo $fileContent;
            }, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => strlen($fileContent),
            ]);

        } catch (\Exception $e) {
            logger('Error downloading file: ' . $e->getMessage());
            $this->error("Failed to download file: {$filename}");
            return;
        }
    }

    public function previewFile($filename)
    {
        if (!$this->document || !isset($this->document['file_path']) || !$this->document['file_path']) {
            $this->error("No file associated with this document");
            return;
        }

        try {
            // Check if file exists on S3/MinIO
            if (!$this->fileExists()) {
                $this->error("File not found: {$filename}");
                return;
            }

            // Check if it's a PDF file
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (strtolower($extension) === 'pdf') {
                // For PDF files, use our watermarked preview route
                $url = route('document.preview-watermarked', [
                    'documentId' => $this->documentId,
                    'filename' => $filename
                ]);
            } else {
                // For non-PDF files, try to use temporary URL or fallback to our route
                try {
                    if (method_exists(Storage::disk('s3'), 'temporaryUrl')) {
                        $url = Storage::disk('s3')->temporaryUrl(
                            $this->document['file_path'],
                            now()->addMinutes(60)
                        );
                    } else {
                        // Fallback to our custom preview route for non-PDF files too
                        $url = route('document.preview-watermarked', [
                            'documentId' => $this->documentId,
                            'filename' => $filename
                        ]);
                    }
                } catch (\Exception $e) {
                    // Fallback to our custom preview route
                    $url = route('document.preview-watermarked', [
                        'documentId' => $this->documentId,
                        'filename' => $filename
                    ]);
                }
            }
            
            // Open the file in a new window/tab
            $this->dispatch('open-preview', url: $url);
            $this->success("Opening preview for: {$filename}");

        } catch (\Exception $e) {
            logger('Error previewing file: ' . $e->getMessage());
            $this->error("Failed to preview file: {$filename}");
        }
    }

    public function shareDocument()
    {
        $this->success("Document link copied to clipboard");
        // In real app, this would copy document URL to clipboard
    }

    public function printDocument()
    {
        $this->success("Opening print dialog");
        // In real app, this would trigger print functionality
    }

    public function bookmarkDocument()
    {
        $this->success("Document bookmarked");
        // In real app, this would save bookmark to user's account
    }

    /**
     * Get the document that revoked this document (if any)
     */
    public function getRevokedByDocument()
    {
        if (!$this->document || !isset($this->document['revoked_by'])) {
            logger("DocumentDetail: No revoked_by data for document " . ($this->document['number'] ?? 'unknown'));
            return null;
        }

        logger("DocumentDetail: Looking for revoking document: " . $this->document['revoked_by']);

        $allDocuments = $this->getAllDocuments();

        // Find the revoking document by number
        foreach ($allDocuments as $doc) {
            if ($doc['number'] === $this->document['revoked_by']) {
                logger("DocumentDetail: Found revoking document: " . $doc['title']);
                return $doc;
            }
        }

        logger("DocumentDetail: Revoking document not found: " . $this->document['revoked_by']);
        return null;
    }

    /**
     * Get the documents that changed this document (if any)
     */
    public function getChangedByDocuments()
    {
        // Debug: Log the full document data
        logger("DocumentDetail: Full document data: " . json_encode($this->document));

        if (!$this->document || !isset($this->document['changed_by']) || empty($this->document['changed_by'])) {
            logger("DocumentDetail: No changed_by data for document " . ($this->document['number'] ?? 'unknown'));
            return [];
        }

        logger("DocumentDetail: Looking for changing documents: " . json_encode($this->document['changed_by']));

        $changedByDocs = [];
        $allDocuments = $this->getAllDocuments();

        foreach ($this->document['changed_by'] as $documentNumber) {
            foreach ($allDocuments as $doc) {
                if ($doc['number'] === $documentNumber) {
                    logger("DocumentDetail: Found changing document: " . $doc['title']);
                    $changedByDocs[] = $doc;
                    break;
                }
            }
        }

        logger("DocumentDetail: Found " . count($changedByDocs) . " changing documents");
        return $changedByDocs;
    }

    /**
     * Get the back URL with pagination and search parameters
     */
    public function getBackUrl()
    {
        $params = [];

        // Use either the component properties or fallback to request/session
        $page = $this->previousPage ?? request()->get('page', session('documents_current_page', 1));
        $search = $this->searchTerm ?? request()->get('search', session('documents_search', ''));

        if ($page && $page > 1) {
            $params['page'] = $page;
        }

        if ($search) {
            $params['search'] = $search;
        }

        return route('document.catalog', $params);
    }

    public function render()
    {
        if (!$this->document) {
            // If document is not loaded, show loading or error state
            $title = 'Document Detail - Loading...';
            $breadcrumbs = [
                ['link' => route('home'), 'label' => 'Home', 'icon' => 's-home'],
                ['label' => 'Documents'],
                ['link' => route('document.catalog'), 'label' => 'Document Catalog'],
                ['label' => 'Loading...'],
            ];

            return view('livewire.document.document-detail', [
                'document' => null,
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        }

        $title = 'Document Detail - ' . ($this->document['number'] ?? 'Unknown');
        $breadcrumbs = [
            ['link' => route('home'), 'label' => 'Home', 'icon' => 's-home'],
            ['label' => 'Documents'],
            ['link' => $this->getBackUrl(), 'label' => 'Document Catalog'],
            ['label' => $this->document['number'] ?? 'Document Detail'],
        ];

        return view('livewire.document.document-detail', [
            'document' => $this->document,
        ])->layout('components.layouts.app', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
