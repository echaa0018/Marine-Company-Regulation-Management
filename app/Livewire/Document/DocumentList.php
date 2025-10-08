<?php

namespace App\Livewire\Document;

use App\Models\Document;
use Livewire\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class DocumentList extends Component
{
    use WithPagination;
    use Toast;
    use WithFileUploads;

    public $search = '';
    public $selectedUser2 = '';
    public $loadingDocumentId = null; // Track which document is being loaded

    // Filter properties
    public $filterType = '';
    public $filterConfidentiality = '';
    public $filterStatus = '';
    public $filterPublishedStart = '';
    public $filterPublishedEnd = '';
    public $filterEffectiveStart = '';
    public $filterEffectiveEnd = '';

    // Store newly created documents in session
    public $createdDocuments = [];

    protected $rules = [];

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
        $this->success("Opened page {$page}");
    }

    public function nextPage($pageName = 'page')
    {
        $this->setPage($this->getPage($pageName) + 1, $pageName);
        $this->success("Opened next page");
    }

    public function previousPage($pageName = 'page')
    {
        $this->setPage($this->getPage($pageName) - 1, $pageName);
        $this->success("Opened previous page");
    }

    public function mount()
    {
        // Load created documents from session
        $this->createdDocuments = session('created_documents', []);

        // Clear old session data that might not have confidentiality field
        $this->ensureSessionDataIntegrity();

        // Handle query parameters for filtering
        $this->handleQueryParameters();
    }

    /**
     * Handle query parameters for pre-filtering
     */
    private function handleQueryParameters()
    {
        $request = request();
        
        if ($request->has('filter_published_start')) {
            $this->filterPublishedStart = $request->get('filter_published_start');
        }
        
        if ($request->has('filter_published_end')) {
            $this->filterPublishedEnd = $request->get('filter_published_end');
        }
        
        if ($request->has('filter_effective_start')) {
            $this->filterEffectiveStart = $request->get('filter_effective_start');
        }
        
        if ($request->has('filter_effective_end')) {
            $this->filterEffectiveEnd = $request->get('filter_effective_end');
        }
    }

    /**
     * Ensure all session documents have required fields
     */
    private function ensureSessionDataIntegrity()
    {
        $createdDocuments = session('created_documents', []);
        $needsUpdate = false;

        foreach ($createdDocuments as &$doc) {
            if (!isset($doc['confidentiality'])) {
                $doc['confidentiality'] = 'Internal Use';
                $needsUpdate = true;
            }
        }

        if ($needsUpdate) {
            session(['created_documents' => $createdDocuments]);
        }
    }

    public function clearSessionData()
    {
        session()->forget(['created_documents', 'document_updates']);
        $this->createdDocuments = [];
        $this->success('Session data cleared successfully');
    }

    public function updatedSearch()
    {
        // Reset to first page when search term changes without showing toast
        $this->setPage(1);
    }

    public function updatedFilterType()
    {
        $this->handleFilterUpdate();
    }

    public function updatedFilterConfidentiality()
    {
        $this->handleFilterUpdate();
    }

    public function updatedFilterStatus()
    {
        $this->handleFilterUpdate();
    }

    public function updatedFilterPublishedStart()
    {
        $this->handleFilterUpdate();
    }

    public function updatedFilterPublishedEnd()
    {
        $this->handleFilterUpdate();
    }

    public function updatedFilterEffectiveStart()
    {
        $this->handleFilterUpdate();
    }

    public function updatedFilterEffectiveEnd()
    {
        $this->handleFilterUpdate();
    }

    private function handleFilterUpdate()
    {
        $this->setPage(1);
        // Don't set isFiltering here - let the wire:loading handle the visual state
    }

    public function updated($propertyName)
    {
        // This runs after any property update is complete
        if (in_array($propertyName, [
            'filterType', 'filterConfidentiality', 'filterStatus',
            'filterPublishedStart', 'filterPublishedEnd',
            'filterEffectiveStart', 'filterEffectiveEnd'
        ])) {
            // Show completion message with short timeout
            $this->success('Filtering complete', position: 'toast-top', timeout: 1500);
        }
    }

    public function clearFilters()
    {
        $this->filterType = '';
        $this->filterConfidentiality = '';
        $this->filterStatus = '';
        $this->filterPublishedStart = '';
        $this->filterPublishedEnd = '';
        $this->filterEffectiveStart = '';
        $this->filterEffectiveEnd = '';
        $this->setPage(1);
        $this->success('All filters cleared successfully', position: 'toast-top');
    }

    #[On('document-created')]
    public function refreshDocuments()
    {
        // This will be called when a document is created
        // You can add logic here to refresh the document list
        $this->success('Document list refreshed!');
    }

    public function getStatusOptions()
    {
        return [
            ['id' => 'Berlaku', 'name' => 'Berlaku'],
            ['id' => 'Tidak Berlaku', 'name' => 'Tidak Berlaku'],
        ];
    }

    public function getTypeOptions()
    {
        return [
            ['id' => 'Peraturan Direksi', 'name' => 'Peraturan Direksi'],
            ['id' => 'Peraturan Direktur', 'name' => 'Peraturan Direktur'],
            ['id' => 'Peraturan Kepala Pimpinan', 'name' => 'Peraturan Kepala Pimpinan'],
            ['id' => 'Standard Operating Procedure', 'name' => 'Standard Operating Procedure'],
            ['id' => 'Business Process', 'name' => 'Business Process'],
            ['id' => 'Form', 'name' => 'Form'],
            ['id' => 'Legislasi', 'name' => 'Legislasi'],
        ];
    }

    public function getConfidentialityOptions()
    {
        return [
            ['id' => 'Confidential', 'name' => 'Confidential'],
            ['id' => 'Internal Use', 'name' => 'Internal Use'],
            ['id' => 'Public', 'name' => 'Public'],
        ];
    }

    public function getDocuments()
    {
        // Get all documents
        $allDocuments = $this->getAllDocuments();

        // Convert to Laravel Collection
        $collection = collect($allDocuments);

        // Sort the collection based on the priority rules
        $collection = $collection->sortBy(function ($document) {
            // Priority 1: Active/Inactive - Active documents (Berlaku) always on top
            $statusOrder = $document['status'] === 'Berlaku' ? 0 : 1;

            // Priority 2: Published date (newest first within each status group)
            // We use a negative timestamp for descending order
            $dateOrder = -strtotime($document['published_date']);

            // Return an array of priorities. PHP will sort by each element in order.
            return [$statusOrder, $dateOrder];
        })->values(); // Use values() to reset array keys for clean pagination

        // Apply search filter if search term exists
        if (!empty($this->search)) {
            $searchTerm = strtolower(trim($this->search));
            $collection = $collection->filter(function($document) use ($searchTerm) {
                // Search only in document title and number (case-insensitive)
                return str_contains(strtolower($document['title']), $searchTerm) ||
                       str_contains(strtolower($document['number']), $searchTerm);
            });
        }

        // Apply filters
        $collection = $this->applyFilters($collection);

        // Use Livewire's built-in pagination with LengthAwarePaginator for collections
        $perPage = 12;
        $currentPage = $this->getPage();
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );

        // Make paginator aware of Livewire
        $paginatedItems->withPath(request()->url());

        return $paginatedItems;
    }

    public function getAllDocuments()
    {
        // Get all documents from database with relationships
        $databaseDocuments = Document::with(['revokes', 'changes', 'revokedBy', 'changedBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($doc) {
                // Transform database model to array format compatible with existing code
                $docArray = [
                    'id' => $doc->id,
                    'number' => $doc->number,
                    'title' => $doc->title,
                    'type' => $doc->type,
                    'published_date' => $doc->published_date->format('Y-m-d'),
                    'effective_until' => $doc->effective_until->format('Y-m-d'),
                    'status' => $doc->status,
                    'confidentiality' => $doc->confidentiality,
                    'file' => $doc->file_name,
                    'file_path' => $doc->file_path,
                    'is_new' => false, // Database documents are not new
                ];

                // Add relationship data in the format expected by existing code
                $docArray['revokes'] = $doc->revokes->pluck('number')->toArray();
                $docArray['changes'] = $doc->changes->pluck('number')->toArray();

                // Add reverse relationship data
                if ($doc->revokedBy->isNotEmpty()) {
                    $docArray['revoked_by'] = $doc->revokedBy->first()->number;
                }

                if ($doc->changedBy->isNotEmpty()) {
                    $docArray['changed_by'] = $doc->changedBy->pluck('number')->toArray();
                }

                return $docArray;
            })
            ->toArray();

        // Load created documents from session and add an 'is_new' flag
        $createdDocsFromSession = session('created_documents', []);
        foreach ($createdDocsFromSession as &$doc) {
            $doc['is_new'] = true;
            // Ensure confidentiality key exists
            if (!isset($doc['confidentiality'])) {
                $doc['confidentiality'] = 'Internal Use';
            }
        }
        unset($doc);

        // Merge session documents with database documents (newest first)
        $allDocuments = array_merge($createdDocsFromSession, $databaseDocuments);

        // Apply dynamic updates from session
        $allDocuments = $this->applyDynamicUpdates($allDocuments);

        return $allDocuments;
    }

    private function applyDynamicUpdates($documents)
    {
        // Get any dynamic updates from session
        $dynamicUpdates = session('document_updates', []);

        // Debug: Log session data
        logger("DocumentList: Applying dynamic updates from session: " . json_encode($dynamicUpdates));

        foreach ($documents as $index => $doc) {
            $docNumber = $doc['number'];
            if (isset($dynamicUpdates[$docNumber])) {
                $updates = $dynamicUpdates[$docNumber];
                logger("DocumentList: Applying updates to document '{$docNumber}': " . json_encode($updates));

                // Apply status update if present
                if (isset($updates['status'])) {
                    $documents[$index]['status'] = $updates['status'];
                    logger("DocumentList: Updated status for '{$docNumber}' to '{$updates['status']}'");
                }

                // Track who revoked this document if present
                if (isset($updates['revoked_by'])) {
                    $documents[$index]['revoked_by'] = $updates['revoked_by'];
                    logger("DocumentList: Set revoked_by for '{$docNumber}' to '{$updates['revoked_by']}'");
                }

                // Track who changed this document if present
                if (isset($updates['changed_by'])) {
                    $documents[$index]['changed_by'] = $updates['changed_by'];
                    logger("DocumentList: Set changed_by for '{$docNumber}' to: " . json_encode($updates['changed_by']));
                }
            }
        }

        return $documents;
    }

    private function applyFilters($collection)
    {
        // Filter by document type
        if (!empty($this->filterType)) {
            $collection = $collection->filter(function($document) {
                return $document['type'] === $this->filterType;
            });
        }

        // Filter by confidentiality
        if (!empty($this->filterConfidentiality)) {
            $collection = $collection->filter(function($document) {
                return ($document['confidentiality'] ?? 'Internal Use') === $this->filterConfidentiality;
            });
        }

        // Filter by status
        if (!empty($this->filterStatus)) {
            $collection = $collection->filter(function($document) {
                return $document['status'] === $this->filterStatus;
            });
        }

        // Filter by published date range
        if (!empty($this->filterPublishedStart) || !empty($this->filterPublishedEnd)) {
            $collection = $collection->filter(function($document) {
                $publishedDate = $document['published_date'];

                $passesStart = empty($this->filterPublishedStart) ||
                              $publishedDate >= $this->filterPublishedStart;

                $passesEnd = empty($this->filterPublishedEnd) ||
                            $publishedDate <= $this->filterPublishedEnd;

                return $passesStart && $passesEnd;
            });
        }

        // Filter by effective until date range
        if (!empty($this->filterEffectiveStart) || !empty($this->filterEffectiveEnd)) {
            $collection = $collection->filter(function($document) {
                $effectiveDate = $document['effective_until'];

                $passesStart = empty($this->filterEffectiveStart) ||
                              $effectiveDate >= $this->filterEffectiveStart;

                $passesEnd = empty($this->filterEffectiveEnd) ||
                            $effectiveDate <= $this->filterEffectiveEnd;

                return $passesStart && $passesEnd;
            });
        }

        return $collection;
    }

    public function viewDetail($documentId)
    {
        logger("🔍 ViewDetail called with documentId: {$documentId}");

        // Show immediate feedback
        $this->success("Loading document...");

        // Set loading state
        $this->loadingDocumentId = $documentId;

        try {
            // First check if document exists in database
            $dbDocument = Document::with(['revokes', 'changes', 'revokedBy', 'changedBy'])
                ->find($documentId);

            if ($dbDocument) {
                logger("✅ ViewDetail: Found document in database with ID {$documentId}: {$dbDocument->title}");

                // Store current pagination and search state for back navigation
                session([
                    'documents_current_page' => $this->getPage(),
                    'documents_search' => $this->search
                ]);

                // Clear loading state
                $this->loadingDocumentId = null;

                // Navigate to document detail page with current state parameters
                $params = ['id' => $documentId];
                if ($this->getPage() > 1) {
                    $params['page'] = $this->getPage();
                }
                if ($this->search) {
                    $params['search'] = $this->search;
                }

                logger("🚀 Redirecting to: " . route('document.detail', $params));
                return $this->redirect(route('document.detail', $params));
            }

            // Fallback: Check session documents
            $allDocuments = $this->getAllDocuments();
            logger("📄 ViewDetail: Found " . count($allDocuments) . " total documents in session");

            $document = collect($allDocuments)->firstWhere('id', $documentId);

            if ($document) {
                logger("✅ ViewDetail: Found document in session with ID {$documentId}: {$document['title']}");
                // Store the document data in session to pass to detail page
                session(['selected_document' => $document]);

                // Store current pagination and search state for back navigation
                session([
                    'documents_current_page' => $this->getPage(),
                    'documents_search' => $this->search
                ]);

                // Clear loading state
                $this->loadingDocumentId = null;

                // Navigate to document detail page with current state parameters
                $params = ['id' => $documentId];
                if ($this->getPage() > 1) {
                    $params['page'] = $this->getPage();
                }
                if ($this->search) {
                    $params['search'] = $this->search;
                }

                logger("🚀 Redirecting to: " . route('document.detail', $params));
                return $this->redirect(route('document.detail', $params));
            } else {
                logger("❌ ViewDetail: Document with ID {$documentId} not found");
                // Clear loading state
                $this->loadingDocumentId = null;
                $this->error('Document not found.');
            }
        } catch (\Exception $e) {
            logger("💥 ViewDetail error: " . $e->getMessage());
            logger("💥 Stack trace: " . $e->getTraceAsString());
            $this->loadingDocumentId = null;
            $this->error('Error loading document: ' . $e->getMessage());
        }
    }

    /**
     * Get document detail URL with current pagination and search state
     */
    public function getDocumentDetailUrl($documentId)
    {
        $params = ['id' => $documentId];

        if ($this->getPage() > 1) {
            $params['page'] = $this->getPage();
        }

        if ($this->search) {
            $params['search'] = $this->search;
        }

        return route('document.detail', $params);
    }

    public function render()
    {
        $title = 'Document Catalog';
        $breadcrumbs = [
            ['link' => route('home'), 'label' => 'Home', 'icon' => 's-home'],
            ['label' => 'Documents'],
            ['label' => 'Document Catalog'],
        ];

        $typeOptions = $this->getTypeOptions();
        $statusOptions = $this->getStatusOptions();
        $confidentialityOptions = $this->getConfidentialityOptions();
        $documents = $this->getDocuments();

        return view('livewire.document.document-list', [
            'typeOptions' => $typeOptions,
            'statusOptions' => $statusOptions,
            'confidentialityOptions' => $confidentialityOptions,
            'documents' => $documents,
        ])->layout('components.layouts.app', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
