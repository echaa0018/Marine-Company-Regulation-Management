<?php

namespace App\Livewire\Document;

use App\Models\Document;
use App\Models\DocumentRelationship;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Mary\Traits\Toast;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('components.layouts.app')]
class CreateDocument extends Component
{
    use Toast;
    use WithFileUploads;

    // Document properties
    public $number = '';
    public $title = '';
    public $type = '';
    public $published_date = '';
    public $effective_until = '';
    public $confidentiality = 'Internal Use';
    public $status = 'Berlaku';
    public $revokes_text = '';
    public $changes_text = '';
    public $file;

    // Searchable multiple properties
    public $revokes_multi_searchable_ids = [];
    public $changes_multi_searchable_ids = [];
    public $revokesMultiSearchable = [];
    public $changesMultiSearchable = [];

    protected $rules = [
        'number' => 'required|string|max:100',
        'title' => 'required|string|max:500',
        'type' => 'required|string',
        'published_date' => 'required|date',
        'effective_until' => 'required|date',
        'confidentiality' => 'required|string',
        'status' => 'required|string',
        // 'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
    ];

    protected $messages = [
        'number.required' => 'Document number is required',
        'number.max' => 'Document number must not exceed 100 characters',
        'title.required' => 'Title is required',
        'type.required' => 'Document type is required',
        'published_date.required' => 'Published date is required',
        'effective_until.required' => 'Effective until date is required',
        'effective_until.after_or_equal' => 'Effective until date cannot be earlier than published date',
        'confidentiality.required' => 'Confidentiality is required',
        'status.required' => 'Status is required',
        // 'file.mimes' => 'File must be in PDF, DOC, or DOCX format',
        // 'file.max' => 'File size must not exceed 10MB',
    ];

    public function mount()
    {
        // Initialize with empty search - will load all documents
        $this->searchRevokes('');
        $this->searchChanges('');
    }

    public function searchRevokes(string $value = '')
    {
        // Debug: Log when search is called
        logger("searchRevokes called with value: '" . $value . "' (length: " . strlen($value) . ")");

        // Get all existing documents (both static and created ones from session)
        $allDocuments = $this->getAllDocuments();

        // Filter to only show documents with "Berlaku" status
        $allDocuments = collect($allDocuments)->filter(function ($doc) {
            return isset($doc['status']) && $doc['status'] === 'Berlaku';
        })->toArray();

        // If no search value or empty/whitespace only, show all Berlaku documents
        if (empty($value) || empty(trim($value))) {
            logger("searchRevokes: Showing all Berlaku documents (empty search)");
            $filteredDocuments = collect($allDocuments);
        } else {
            logger("searchRevokes: Filtering Berlaku documents with search term");
            // Filter documents based on search value - focus on number and title
            $filteredDocuments = collect($allDocuments)
                ->filter(function ($doc) use ($value) {
                    $searchText = strtolower(trim($value));
                    return str_contains(strtolower($doc['number']), $searchText) ||
                           str_contains(strtolower($doc['title']), $searchText);
                });
        }

        // Always include already selected documents in the options
        if (!empty($this->revokes_multi_searchable_ids)) {
            $selectedDocuments = collect($allDocuments)
                ->whereIn('id', $this->revokes_multi_searchable_ids);

            // Merge selected with filtered, remove duplicates
            $filteredDocuments = $selectedDocuments->concat($filteredDocuments)->unique('id');
        }

        $this->revokesMultiSearchable = $filteredDocuments
            ->map(function ($doc) {
                return [
                    'id' => $doc['id'],
                    'name' => $doc['number'] . ' - ' . $doc['title'], // Display as "[number - title]" format
                    'description' => ($doc['confidentiality'] ?? 'Internal Use') . ' | ' . $doc['type'] // Show confidentiality and type as description
                ];
            })
            ->take(50) // Increase limit to accommodate selected + search results
            ->values()
            ->toArray();

        logger("searchRevokes: Returning " . count($this->revokesMultiSearchable) . " documents");
    }

    public function searchChanges(string $value = '')
    {
        // Debug: Log when search is called
        logger("searchChanges called with value: '" . $value . "' (length: " . strlen($value) . ")");

        // Get all existing documents (both static and created ones from session) for changes
        $allDocuments = $this->getAllDocuments();

        // Filter to only show documents with "Berlaku" status
        $allDocuments = collect($allDocuments)->filter(function ($doc) {
            return isset($doc['status']) && $doc['status'] === 'Berlaku';
        })->toArray();

        // If no search value or empty/whitespace only, show all Berlaku documents
        if (empty($value) || empty(trim($value))) {
            logger("searchChanges: Showing all Berlaku documents (empty search)");
            $filteredDocuments = collect($allDocuments);
        } else {
            logger("searchChanges: Filtering Berlaku documents with search term");
            // Filter documents based on search value - focus on number and title
            $filteredDocuments = collect($allDocuments)
                ->filter(function ($doc) use ($value) {
                    $searchText = strtolower(trim($value));
                    return str_contains(strtolower($doc['number']), $searchText) ||
                           str_contains(strtolower($doc['title']), $searchText);
                });
        }

        // Always include already selected documents in the options
        if (!empty($this->changes_multi_searchable_ids)) {
            $selectedDocuments = collect($allDocuments)
                ->whereIn('id', $this->changes_multi_searchable_ids);

            // Merge selected with filtered, remove duplicates
            $filteredDocuments = $selectedDocuments->concat($filteredDocuments)->unique('id');
        }

        $this->changesMultiSearchable = $filteredDocuments
            ->map(function ($doc) {
                return [
                    'id' => $doc['id'],
                    'name' => $doc['number'] . ' - ' . $doc['title'], // Display as "[number - title]" format
                    'description' => ($doc['confidentiality'] ?? 'Internal Use') . ' | ' . $doc['type'] // Show confidentiality and type as description
                ];
            })
            ->take(50) // Increase limit to accommodate selected + search results
            ->values()
            ->toArray();

        logger("searchChanges: Returning " . count($this->changesMultiSearchable) . " documents");
    }

    // Property update hooks to refresh options when selections change
    public function updatedRevokesMultiSearchableIds()
    {
        // Refresh options to maintain selected items in dropdown
        $this->searchRevokes('');
        // Validate that there's no overlap between revokes and changes
        $this->validateRevokeChangesOverlap();
    }

    public function updatedChangesMultiSearchableIds()
    {
        // Refresh options to maintain selected items in dropdown
        $this->searchChanges('');
        // Validate that there's no overlap between revokes and changes
        $this->validateRevokeChangesOverlap();
    }

    private function getAllDocuments()
    {
        // Use DocumentList as the single source of truth
        $documentListComponent = new DocumentList();
        return $documentListComponent->getAllDocuments();
    }

    /**
     * Check if document number already exists
     */
    private function validateDocumentNumberUnique()
    {
        // Clear existing errors for this field first
        $this->resetErrorBag(['number']);

        if (empty($this->number)) {
            return true; // Don't validate if empty (handled by required validation)
        }

        // Check database first
        $existingInDatabase = Document::where('number', 'ILIKE', trim($this->number))->first();
        if ($existingInDatabase) {
            $this->addError('number', 'This document number already exists in the database. Please use a different number.');
            return false;
        }

        // Check session documents
        $allDocuments = $this->getAllDocuments();
        $existingDocument = collect($allDocuments)->first(function ($doc) {
            return strtolower(trim($doc['number'])) === strtolower(trim($this->number));
        });

        if ($existingDocument) {
            $this->addError('number', 'This document number already exists. Please use a different number.');
            return false;
        }

        return true;
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

    public function resetForm()
    {
        $this->reset([
            'number', 'title', 'type', 'published_date', 'effective_until',
            'confidentiality', 'revokes_text', 'changes_text', 'file',
            'revokes_multi_searchable_ids', 'changes_multi_searchable_ids'
        ]);
        $this->status = 'Berlaku';
        $this->confidentiality = 'Internal Use';
        // Reinitialize the searchable options
        $this->searchRevokes('');
        $this->searchChanges('');
    }

    /**
     * Real-time validation when number is updated
     */
    public function updatedNumber()
    {
        if (!empty($this->number)) {
            $this->resetErrorBag(['number']);
            // Validate that the document number is unique
            $this->validateDocumentNumberUnique();
        }
    }

    /**
     * Real-time validation when title is updated
     */
    public function updatedTitle()
    {
        if (!empty($this->title)) {
            $this->resetErrorBag(['title']);
        }
    }

    /**
     * Real-time validation when type is updated
     */
    public function updatedType()
    {
        if (!empty($this->type)) {
            $this->resetErrorBag(['type']);
        }
    }

    /**
     * Real-time validation when confidentiality is updated
     */
    public function updatedConfidentiality()
    {
        if (!empty($this->confidentiality)) {
            $this->resetErrorBag(['confidentiality']);
        }
    }

    /**
     * Real-time validation when status is updated
     */
    public function updatedStatus()
    {
        if (!empty($this->status)) {
            $this->resetErrorBag(['status']);
        }
    }

    /**
     * Real-time validation when published_date is updated
     */
    public function updatedPublishedDate()
    {
        // Clear existing errors for this field first
        $this->resetErrorBag(['published_date']);

        // Also validate effective date since it depends on published date
        if (!empty($this->effective_until)) {
            $this->resetErrorBag(['effective_until']);
            $this->validateEffectiveUntilDate();
        }
    }

    /**
     * Real-time validation when effective_until is updated
     */
    public function updatedEffectiveUntil()
    {
        // Clear existing errors for this field first
        $this->resetErrorBag(['effective_until']);

        // Validate effective until date
        $this->validateEffectiveUntilDate();
    }

    /**
     * Custom validation method to check for overlapping documents in revokes and changes
     */
    protected function validateRevokeChangesOverlap()
    {
        // Clear any existing errors first
        $this->resetErrorBag(['revokes_multi_searchable_ids', 'changes_multi_searchable_ids']);

        // Get selected IDs
        $revokeIds = $this->revokes_multi_searchable_ids ?? [];
        $changeIds = $this->changes_multi_searchable_ids ?? [];

        // Find overlapping IDs
        $overlappingIds = array_intersect($revokeIds, $changeIds);

        if (!empty($overlappingIds)) {
            // Get document details for better error messages
            $allDocuments = $this->getAllDocuments();
            $overlappingTitles = [];

            foreach ($overlappingIds as $id) {
                $doc = collect($allDocuments)->firstWhere('id', $id);
                if ($doc) {
                    $overlappingTitles[] = $doc['title'];
                }
            }

            $errorMessage = 'The following document(s) cannot be in both Revokes and Changes: ' . implode(', ', $overlappingTitles);

            // Add error to only one field to avoid duplicate messages
            $this->addError('revokes_multi_searchable_ids', $errorMessage);

            return false;
        } else {
            // Clear errors if no overlap and ensure no "required" errors for these optional fields
            $this->resetErrorBag(['revokes_multi_searchable_ids', 'changes_multi_searchable_ids']);
        }

        return true;
    }

    /**
     * Custom validation method to check effective date against published date
     */
    protected function validateEffectiveUntilDate()
    {
        // Parse both dates
        $publishedDate = $this->published_date;
        $effectiveUntil = $this->effective_until;

        // Only validate if both dates are provided
        if (!empty($publishedDate) && !empty($effectiveUntil)) {
            // Compare dates
            if ($effectiveUntil < $publishedDate) {
                $this->addError('effective_until', 'Effective until date cannot be earlier than published date (' . $publishedDate . ')');
                return false;
            } else {
                // Clear the error if validation passes
                $this->resetErrorBag(['effective_until']);
            }
        }

        return true;
    }

    public function createDocument()
    {
        // Ensure revokes and changes fields never show "required" errors since they're optional
        $this->resetErrorBag(['revokes_multi_searchable_ids', 'changes_multi_searchable_ids']);

        // First run the standard validation
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Check if there are any required field errors and add specific field-level errors
            $errors = $e->errors();
            $hasRequiredFieldErrors = false;

            // Add specific field-level required errors for empty required fields
            if (empty($this->number)) {
                $this->addError('number', 'Document number is required');
                $hasRequiredFieldErrors = true;
            }
            if (empty($this->title)) {
                $this->addError('title', 'Title is required');
                $hasRequiredFieldErrors = true;
            }
            if (empty($this->type)) {
                $this->addError('type', 'Document type is required');
                $hasRequiredFieldErrors = true;
            }
            if (empty($this->published_date)) {
                $this->addError('published_date', 'Published date is required');
                $hasRequiredFieldErrors = true;
            }
            if (empty($this->effective_until)) {
                $this->addError('effective_until', 'Effective until date is required');
                $hasRequiredFieldErrors = true;
            }
            if (empty($this->confidentiality)) {
                $this->addError('confidentiality', 'Confidentiality is required');
                $hasRequiredFieldErrors = true;
            }
            if (empty($this->status)) {
                $this->addError('status', 'Status is required');
                $hasRequiredFieldErrors = true;
            }

            // Ensure revokes and changes never show required errors (they're optional)
            $this->resetErrorBag(['revokes_multi_searchable_ids', 'changes_multi_searchable_ids']);

            // Check for other validation errors (like max length)
            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    if (!str_contains($message, 'must be filled') && !str_contains($message, 'required')) {
                        // This is a non-required validation error, keep the original message
                        $hasRequiredFieldErrors = false; // Mixed errors
                        break 2;
                    }
                }
            }

            if ($hasRequiredFieldErrors) {
                $this->error('Please fill out all required fields before saving the document.');
            } else {
                $this->error('Please fix the validation errors before saving the document.');
            }
            return;
        }

        // Validate effective until date
        if (!$this->validateEffectiveUntilDate()) {
            $this->error('Please ensure the effective until date is valid before saving.');
            return; // Stop execution if validation fails
        }

        // Validate no overlap between revokes and changes
        if (!$this->validateRevokeChangesOverlap()) {
            $this->error('Please resolve the document selection conflicts before saving.');
            return; // Stop execution if validation fails
        }

        // Validate document number uniqueness
        if (!$this->validateDocumentNumberUnique()) {
            $this->error('Please use a unique document number before saving.');
            return; // Stop execution if validation fails
        }
        try {
            // Handle file upload
           
            $fileName = null;
            $filePath = null;
            if ($this->file) {
                try{
                    logger("TES");
                    // Get the original filename from the uploaded file
                    $fileName = $this->file->getClientOriginalName();

                    // Store the file in the 'documents' folder with its original name on the 's3' disk
                    $filePath = $this->file->storeAs('documents', $fileName, 's3');
                } catch (\Exception $e) {
                    logger($e->getMessage());
                    $this->error('Failed to upload document file.' . $e->getMessage());
                    return; // Stop execution if upload fails
                }
            }

            // Create the document in database
            $document = Document::create([
                'id' => Str::uuid(),
                'number' => $this->number,
                'title' => $this->title,
                'type' => $this->type,
                'published_date' => $this->published_date,
                'effective_until' => $this->effective_until,
                'confidentiality' => $this->confidentiality,
                'status' => $this->status,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'created_by' => 'system',
            ]);

            // Process revokes relationships
            if (!empty($this->revokes_multi_searchable_ids)) {
                $allDocuments = $this->getAllDocuments();
                foreach ($this->revokes_multi_searchable_ids as $revokedId) {
                    $revokedDoc = collect($allDocuments)->firstWhere('id', $revokedId);
                    if ($revokedDoc) {
                        // Find the document in database
                        $targetDocument = Document::where('number', $revokedDoc['number'])->first();
                        if ($targetDocument) {
                            // Create revokes relationship
                            DocumentRelationship::create([
                                'id' => Str::uuid(),
                                'source_document_id' => $document->id,
                                'target_document_id' => $targetDocument->id,
                                'relationship_type' => DocumentRelationship::TYPE_REVOKES,
                                'created_by' => 'system',
                            ]);

                            // Update the revoked document status
                            $targetDocument->update([
                                'status' => 'Tidak Berlaku',
                                'revoked_by' => $document->number,
                                'updated_by' => 'system',
                            ]);
                        }
                    }
                }
            }

            // Process changes relationships
            if (!empty($this->changes_multi_searchable_ids)) {
                $allDocuments = $this->getAllDocuments();
                foreach ($this->changes_multi_searchable_ids as $changeId) {
                    $changeDoc = collect($allDocuments)->firstWhere('id', $changeId);
                    if ($changeDoc) {
                        // Find the document in database
                        $targetDocument = Document::where('number', $changeDoc['number'])->first();
                        if ($targetDocument) {
                            // Create changes relationship
                            DocumentRelationship::create([
                                'id' => Str::uuid(),
                                'source_document_id' => $document->id,
                                'target_document_id' => $targetDocument->id,
                                'relationship_type' => DocumentRelationship::TYPE_CHANGES,
                                'created_by' => 'system',
                            ]);
                        }
                    }
                }
            }

            $this->success('Document created successfully!');

            // Redirect to document catalog
            return $this->redirect(route('document.catalog'));

        } catch (\Exception $e) {
            dd($e); 
            logger('Error creating document: ' . $e->getMessage());
            $this->error('Failed to create document. Please try again.');
        }
    }

    public function render()
    {
        $title = 'Create New Document';
        $breadcrumbs = [
            ['link' => route('home'), 'label' => 'Home', 'icon' => 's-home'],
            ['label' => 'Documents'],
            ['link' => route('document.catalog'), 'label' => 'Document Catalog'],
            ['label' => 'Create Document']
        ];
        return view('livewire.document.create-document', [
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
