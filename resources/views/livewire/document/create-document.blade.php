<div class="min-h-screen from-base-200/30 via-base-100/50 to-primary/5">
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 animate-fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-sm text-base-content/60 font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Documents</span>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Create New</span>
                    </div>
                    <h1 class="text-4xl font-bold flex items-center gap-3 bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
                        <span class="p-3 bg-gradient-to-br from-primary/20 to-accent/20 rounded-xl backdrop-blur-sm border border-primary/20 shadow-lg hover:scale-105 transition-transform duration-300">
                            <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </span>
                        Create New Document
                    </h1>
                    <p class="text-base-content/70 mt-3 text-lg">Fill out the form below to add a new document to the system</p>
                </div>
                <div>
                    <x-button
                        label="Back to Document Catalog"
                        icon="o-arrow-left"
                        class="btn-outline btn-sm sm:btn-md hover:scale-105 transition-all duration-300 shadow-md hover:shadow-lg"
                        wire:navigate href="{{ route('document.catalog') }}"
                        spinner />
                </div>
            </div>
        </div>

        {{-- Main Form Card --}}
        <div class="card bg-base-100 shadow-2xl border border-base-300/50 backdrop-blur-sm hover:shadow-3xl transition-all duration-500 animate-slide-up">
            <div class="card-body p-6 sm:p-8">
                {{-- Progress Indicator --}}
                {{-- <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-primary-content text-sm font-bold shadow-lg">
                                1
                            </div>
                            <span class="ml-2 text-sm font-medium text-primary">Document Info</span>
                        </div>
                        <div class="w-16 h-1 bg-primary/20 rounded-full">
                            <div class="w-full h-full bg-primary rounded-full"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center text-base-content/60 text-sm font-bold">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium text-base-content/60">Relationships</span>
                        </div>
                        <div class="w-16 h-1 bg-primary/20 rounded-full"></div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center text-base-content/60 text-sm font-bold">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium text-base-content/60">File Upload</span>
                        </div>
                    </div>
                </div> --}}

                <x-form wire:submit="createDocument" no-separator novalidate>

                      <div class="flex items-center gap-3 mb-6 p-4 bg-gradient-to-r from-primary/10 to-accent/10 rounded-xl border border-primary/20 backdrop-blur-sm">
                          <div class="w-2 h-8 bg-gradient-to-b from-primary to-accent rounded-full"></div>
                          <div class="flex items-center gap-2">
                              <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                              </svg>
                              <h2 class="text-xl font-bold text-base-content">Document Information</h2>
                          </div>
                      </div>

                    {{-- Document Details Section --}}
                    <div class="mb-10 p-6 bg-base-50/50 rounded-xl border border-base-200 hover:border-primary/30 transition-all duration-300">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                            {{-- Document Title --}}
                            <div class="lg:col-span-2 group">
                              <x-input
                                  label="Document Title"
                                  wire:model="title"
                                  placeholder="Enter a descriptive title for your document..."
                                  class="input-bordered focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-300"
                                  required />
                            </div>

                            {{-- Document Number --}}
                            <div class="lg:col-span-2 group">
                                <x-input
                                    label="Document Number"
                                    wire:model.live.debounce.500ms="number"
                                    placeholder="e.g., PER-2/LGL/03/2023"
                                    hint="Format: [TYPE-NUMBER]/[DEPT]/[MONTH]/[YEAR]"
                                    icon="o-hashtag"
                                    class="input-bordered focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-300"
                                    required />
                            </div>

                            {{-- Document Type --}}
                            <div class="lg:col-span-2 group">
                                <x-select
                                    label="Document Type"
                                    wire:model="type"
                                    :options="$this->getTypeOptions()"
                                    option-value="id"
                                    option-label="name"
                                    placeholder="Select document type..."
                                    icon="o-document"
                                    class="select-bordered focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-300"
                                    required />
                            </div>

                            {{-- Status --}}
                            <div class="group">
                                <x-select
                                    label="Status"
                                    wire:model="status"
                                    :options="$this->getStatusOptions()"
                                    option-value="id"
                                    option-label="name"
                                    placeholder="Select status..."
                                    icon="o-flag"
                                    class="select-bordered focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-300"
                                    required />
                            </div>

                            {{-- Confidentiality --}}
                            <div class="group">
                                <x-select
                                    label="Confidentiality Level"
                                    wire:model="confidentiality"
                                    :options="$this->getConfidentialityOptions()"
                                    option-value="id"
                                    option-label="name"
                                    placeholder="Select confidentiality level..."
                                    icon="o-lock-closed"
                                    class="select-bordered focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-300"
                                    required />
                            </div>
                        </div>

                        {{-- Date Fields --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8 p-4 bg-gradient-to-r from-info/5 to-accent/5 rounded-lg border border-info/20">
                            <div class="lg:col-span-2 mb-2">
                                <div class="flex items-center gap-2 text-info font-medium">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Document Dates</span>
                                </div>
                            </div>
                            {{-- Published Date --}}
                            <div class="group">
                                <x-datetime
                                    label="Published Date"
                                    wire:model.live.debounce.500ms="published_date"
                                    icon="o-calendar"
                                    hint="When was this document officially published?"
                                    class="input-bordered focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-300"
                                    required />
                            </div>

                            {{-- Effective Until Date --}}
                            <div class="group">
                                <x-datetime
                                    label="Effective Until"
                                    wire:model.live.debounce.500ms="effective_until"
                                    icon="o-calendar"
                                    hint="When does this document expire?"
                                    class="input-bordered focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-300"
                                    required />
                            </div>
                        </div>
                    </div>

                    {{-- Document Relationships Section --}}
                    <div class="mb-10 p-6 bg-gradient-to-br from-accent/5 to-secondary/5 rounded-xl border border-accent/20 hover:border-accent/40 transition-all duration-300">
                        <div class="flex items-center gap-3 mb-6 p-3 bg-gradient-to-r from-accent/10 to-secondary/10 rounded-lg">
                            <div class="w-2 h-8 bg-gradient-to-b from-accent to-secondary rounded-full"></div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                <h2 class="text-xl font-bold text-base-content">Document Relationships</h2>
                            </div>
                            <div class="ml-auto flex gap-2">
                                <div class="badge badge-ghost badge-sm">Optional</div>
                            </div>
                        </div>

                        <div class="space-y-8">
                            {{-- Revokes --}}
                            <div class="space-y-3 p-4 bg-red-50/50 rounded-lg border border-red-200/50 hover:border-red-300 transition-all duration-300">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="font-medium text-red-700">Documents to Revoke</span>
                                </div>
                                <x-choices
                                    label="Revokes"
                                    wire:model.live="revokes_multi_searchable_ids"
                                    :options="$revokesMultiSearchable"
                                    placeholder="Search by document number or title..."
                                    search-function="searchRevokes"
                                    no-result-text="No documents found..."
                                    hint="Documents that this new document will revoke/replace"
                                    debounce="300ms"
                                    searchable
                                    multiple />
                                <div class="text-xs text-red-600 flex items-center gap-1 mt-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Cannot overlap with Changes section
                                </div>
                            </div>

                            {{-- Changes --}}
                            <div class="space-y-3 p-4 bg-green-50/50 rounded-lg border border-green-200/50 hover:border-green-300 transition-all duration-300">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span class="font-medium text-green-700">Documents to Change/Amend</span>
                                </div>
                                <x-choices
                                    label="Changes/Amends"
                                    wire:model.live="changes_multi_searchable_ids"
                                    :options="$changesMultiSearchable"
                                    placeholder="Search by document number or title..."
                                    search-function="searchChanges"
                                    no-result-text="No documents found..."
                                    hint="Documents that this new document will change/amend"
                                    debounce="300ms"
                                    searchable
                                    multiple />
                                <div class="text-xs text-green-600 flex items-center gap-1 mt-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Cannot overlap with Revokes section
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- File Upload Section --}}
                    <div class="mb-10 p-6 bg-gradient-to-br from-info/5 to-primary/5 rounded-xl border border-info/20 hover:border-info/40 transition-all duration-300">
                        <div class="flex items-center gap-3 mb-6 p-3 bg-gradient-to-r from-info/10 to-primary/10 rounded-lg">
                            <div class="w-2 h-8 bg-gradient-to-b from-info to-primary rounded-full"></div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <h2 class="text-xl font-bold text-base-content">Document File</h2>
                            </div>
                            <div class="ml-auto flex gap-2">
                                <div class="badge badge-ghost badge-sm">Optional</div>
                            </div>
                        </div>

                        <div class="border-2 border-dashed border-info/30 rounded-xl p-8 bg-gradient-to-br from-info/5 to-primary/5 hover:border-info/50 hover:bg-info/10 transition-all duration-300 group">
                            <div class="text-center space-y-4">
                                <div class="w-16 h-16 mx-auto bg-info/10 rounded-full flex items-center justify-center group-hover:bg-info/20 transition-all duration-300">
                                    <svg class="w-8 h-8 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-base-content mb-2">Upload Document File</h3>
                                    <p class="text-base-content/60 text-sm">
                                        Drag and drop your PDF file here, or click to browse
                                    </p>
                                </div>
                            </div>
                            <x-file
                                label=""
                                wire:model="file"
                                accept="application/pdf"
                                hint="Accepted formats: PDF • Maximum size: 10MB • Leave empty for testing purposes"
                                class="file-input-bordered mt-4" />
                        </div>
                    </div>

                    {{-- Validation Errors --}}
                    <div class="mb-6">
                        <x-errors />
                    </div>

                    {{-- Actions --}}
                    <div class="border-t border-base-300 pt-8 mt-8">
                        <div class="flex flex-col sm:flex-row justify-end gap-4">
                            <x-button
                                label="Cancel"
                                link="{{ route('document.catalog') }}"
                                class="btn-ghost order-2 sm:order-1 hover:bg-base-200 hover:scale-105 transition-all duration-300"
                                icon="o-x-mark" />

                            <x-button
                                label="Create Document"
                                type="submit"
                                icon="o-document-plus"
                                class="btn-primary order-1 sm:order-2 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300"
                                spinner="createDocument" />
                        </div>
                    </div>
                </x-form>
            </div>
        </div>
    </div>

    {{-- Styles --}}
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
        
        .animate-slide-up {
            animation: slide-up 0.8s ease-out;
        }
        
        .group:hover .group-hover\:scale-105 {
            transform: scale(1.05);
        }
        
        .input-bordered:focus, .select-bordered:focus {
            box-shadow: 0 0 0 3px rgba(var(--p) / 0.1);
        }
    </style>
</div>
