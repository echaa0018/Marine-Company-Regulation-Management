<div class="min-h-screen">
    @if(!$document)
        <div class="flex flex-col items-center justify-center min-h-screen px-4">
            <div class="text-center max-w-md mx-auto">
                <div class="w-24 h-24 mx-auto mb-8 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center">
                    <x-icon name="o-exclamation-triangle" class="w-12 h-12 text-red-500" />
                </div>
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">Document not found</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-8 text-lg">
                    The requested document could not be found or you don't have permission to view it.
                </p>
                <x-button
                    label="Back to Documents"
                    icon="o-arrow-left"
                    link="{{ $this->getBackUrl() }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl" />
            </div>
        </div>
    @else

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button - Moved to the right -->
        <div class="flex justify-end mb-4">
            <x-button
                label="Back to Document Catalog"
                icon="o-arrow-left"
                class="btn-outline btn-sm sm:btn-md hover:scale-105 transition-all duration-300 shadow-md hover:shadow-lg"
                wire:navigate 
                href="{{ $this->getBackUrl() }}"
                spinner />
        </div>

        <!-- Document Header -->
        <div class="mb-4">

            <!-- Status and Type Badges -->
            <div class="flex flex-wrap items-center gap-3 mb-4">
                @if($document['status'] === 'Berlaku')
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-sm font-medium border border-green-200 dark:border-green-800">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        Active
                    </span>
                @elseif($document['status'] === 'Tidak Berlaku')
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full text-sm font-medium border border-red-200 dark:border-red-800">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        Inactive
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 rounded-full text-sm font-medium border border-yellow-200 dark:border-yellow-800">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
                        {{ $document['status'] }}
                    </span>
                @endif

                @if(($document['confidentiality'] ?? 'Internal Use') === 'Confidential')
                    <span class="px-3 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full text-sm font-medium border border-red-200 dark:border-red-800">
                        🔒 Confidential
                    </span>
                @elseif(($document['confidentiality'] ?? 'Internal Use') === 'Internal Use')
                    <span class="px-3 py-1.5 bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded-full text-sm font-medium border border-orange-200 dark:border-orange-800">
                        🏢 Internal Use
                    </span>
                @else
                    <span class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm font-medium border border-blue-200 dark:border-blue-800">
                        {{ $document['confidentiality'] ?? 'Internal Use' }}
                    </span>
                @endif

                <span class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-full text-sm font-medium border border-slate-200 dark:border-slate-700">
                    {{ $document['type'] }}
                </span>
            </div>

            <!-- Document Number -->
            <div class="mb-2">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg border border-blue-200 dark:border-blue-800 font-mono text-lg font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                    {{ $document['number'] }}
                </span>
            </div>

            <!-- Document Title -->
            <h1 class="text-4xl md:text-5xl font-bold text-slate-900 dark:text-white leading-tight mb-4">
                {{ $document['title'] }}
            </h1>

            <!-- Metadata Quick Info -->
            <div class="flex flex-wrap items-center gap-6 text-sm text-slate-600 dark:text-slate-400 pb-8 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm6 0a2 2 0 100-4 2 2 0 000 4z"></path>
                    </svg>
                    Published {{ \Carbon\Carbon::parse($document['published_date'])->format('M d, Y') }}
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Valid until {{ \Carbon\Carbon::parse($document['effective_until'])->format('M d, Y') }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-10">
                <!-- Document File Section -->
                @if(isset($document['file']) && $document['file'])
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 card-hover">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            Document File
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $document['file'] }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">PDF Document</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button
                                    wire:click="previewFile('{{ $document['file'] }}')"
                                    class="px-4 py-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors duration-200 flex items-center gap-2 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Preview
                                </button>
                                <button
                                    wire:click="downloadFile('{{ $document['file'] }}')"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center gap-2 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Status Updates Section -->
                @php
                    $revokedByDoc = $this->getRevokedByDocument();
                    $changedByDocs = $this->getChangedByDocuments();
                @endphp
                
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 card-hover">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Document Status Updates
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($revokedByDoc || count($changedByDocs) > 0)
                            <div class="space-y-4">
                                @if($revokedByDoc)
                                    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-red-800 dark:text-red-200 mb-1">Document Revoked</h4>
                                                <p class="text-red-700 dark:text-red-300 text-sm">
                                                    This document has been revoked by 
                                                    <a href="{{ route('document.detail', ['id' => $revokedByDoc['id']]) }}"
                                                       class="font-semibold underline hover:no-underline transition-colors duration-200">
                                                        {{ $revokedByDoc['number'] }} - {{ $revokedByDoc['title'] }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @foreach($changedByDocs as $changeDoc)
                                    <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-green-800 dark:text-green-200 mb-1">Document Updated</h4>
                                                <p class="text-green-700 dark:text-green-300 text-sm">
                                                    This document has been updated by 
                                                    <a href="{{ route('document.detail', ['id' => $changeDoc['id']]) }}"
                                                       class="font-semibold underline hover:no-underline transition-colors duration-200">
                                                        {{ $changeDoc['number'] }} - {{ $changeDoc['title'] }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 font-medium">No status updates available</p>
                                <p class="text-slate-500 dark:text-slate-500 text-sm mt-1">This document is in its original state</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Documents Section -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 card-hover">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v0M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                </svg>
                            </div>
                            Related Documents
                        </h2>
                    </div>
                    <div class="p-6">
                        @if(count($document['revokes']) > 0 || count($document['changes']) > 0)
                            <div class="space-y-6">
                                @if(count($document['revokes']) > 0)
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-3 h-3 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </span>
                                        Documents Revoked
                                        <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full text-xs font-medium">
                                            {{ count($document['revokes']) }}
                                        </span>
                                    </h3>
                                    <div class="space-y-3">
                                        @foreach($document['revokes'] as $index => $revokedDoc)
                                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 transition-colors duration-200">
                                            <span class="w-6 h-6 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-full text-xs font-semibold flex items-center justify-center flex-shrink-0 mt-1">
                                                {{ $index + 1 }}
                                            </span>
                                            @php
                                                $docInfo = $this->findDocumentForLink($revokedDoc);
                                            @endphp
                                            @if($docInfo)
                                                <a href="{{ route('document.detail', ['id' => $docInfo['id']]) }}"
                                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 font-medium hover:underline transition-colors duration-200 flex-1">
                                                    <span class="font-semibold">{{ $docInfo['number'] }}</span> - {{ $docInfo['title'] }}
                                                </a>
                                            @else
                                                <p class="text-slate-700 dark:text-slate-300 flex-1">{{ $revokedDoc }}</p>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if(count($document['changes']) > 0)
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </span>
                                        Changes Made to
                                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-xs font-medium">
                                            {{ count($document['changes']) }}
                                        </span>
                                    </h3>
                                    <div class="space-y-3">
                                        @foreach($document['changes'] as $index => $change)
                                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 transition-colors duration-200">
                                            <span class="w-6 h-6 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-full text-xs font-semibold flex items-center justify-center flex-shrink-0 mt-1">
                                                {{ $index + 1 }}
                                            </span>
                                            @php
                                                $docInfo = $this->findDocumentForLink($change);
                                            @endphp
                                            @if($docInfo)
                                                <a href="{{ route('document.detail', ['id' => $docInfo['id']]) }}"
                                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 font-medium hover:underline transition-colors duration-200 flex-1">
                                                    <span class="font-semibold">{{ $docInfo['number'] }}</span> - {{ $docInfo['title'] }}
                                                </a>
                                            @else
                                                <p class="text-slate-700 dark:text-slate-300 flex-1">{{ $change }}</p>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v0M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 font-medium">No related documents found</p>
                                <p class="text-slate-500 dark:text-slate-500 text-sm mt-1">This document stands alone</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 sticky top-8">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            Metadata
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-6">
                            <div class="group">
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    Document Number
                                </dt>
                                <dd class="text-slate-900 dark:text-white font-mono text-sm bg-slate-50 dark:bg-slate-900/50 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700">
                                    {{ $document['number'] }}
                                </dd>
                            </div>

                            <div class="group">
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Document Type
                                </dt>
                                <dd class="text-slate-900 dark:text-white">
                                    <span class="inline-block w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium border border-slate-200 dark:border-slate-700 break-words leading-relaxed">
                                        {{ $document['type'] }}
                                    </span>
                                </dd>
                            </div>

                            <div class="group">
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Confidentiality
                                </dt>
                                <dd class="text-slate-900 dark:text-white">
                                    @if(($document['confidentiality'] ?? 'Internal Use') === 'Confidential')
                                        <span class="px-3 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg text-sm font-medium border border-red-200 dark:border-red-800">
                                            🔒 Confidential
                                        </span>
                                    @elseif(($document['confidentiality'] ?? 'Internal Use') === 'Internal Use')
                                        <span class="px-3 py-1.5 bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded-lg text-sm font-medium border border-orange-200 dark:border-orange-800">
                                            🏢 Internal Use
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-medium border border-blue-200 dark:border-blue-800">
                                            {{ $document['confidentiality'] ?? 'Internal Use' }}
                                        </span>
                                    @endif
                                </dd>
                            </div>

                            <div class="group">
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm6 0a2 2 0 100-4 2 2 0 000 4z"></path>
                                    </svg>
                                    Published Date
                                </dt>
                                <dd class="text-slate-900 dark:text-white font-medium">
                                    {{ \Carbon\Carbon::parse($document['published_date'])->format('F d, Y') }}
                                </dd>
                            </div>

                            <div class="group">
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Effective Until
                                </dt>
                                <dd class="text-slate-900 dark:text-white font-medium">
                                    {{ \Carbon\Carbon::parse($document['effective_until'])->format('F d, Y') }}
                                </dd>
                            </div>

                            <div class="group">
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Status
                                </dt>
                                <dd class="text-slate-900 dark:text-white">
                                    @if($document['status'] === 'Berlaku')
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm font-medium border border-green-200 dark:border-green-800">
                                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                            Active
                                        </span>
                                    @elseif($document['status'] === 'Tidak Berlaku')
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg text-sm font-medium border border-red-200 dark:border-red-800">
                                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                            Inactive
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 rounded-lg text-sm font-medium border border-yellow-200 dark:border-yellow-800">
                                            <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
                                            {{ $document['status'] }}
                                        </span>
                                    @endif
                                </dd>
                            </div>

                            @if($document['file'])
                            <div class="group">
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    File
                                </dt>
                                <dd class="text-slate-900 dark:text-white font-mono text-xs bg-slate-50 dark:bg-slate-900/50 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 break-all">
                                    {{ $document['file'] }}
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Scripts -->
    <script>
        document.addEventListener('livewire:initialized', function () {
            // Listen for the open-preview event
            Livewire.on('open-preview', (data) => {
                window.open(data.url, '_blank');
            });

            // Add smooth scroll behavior for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading states for interactive elements
            document.querySelectorAll('[wire\\:click]').forEach(element => {
                element.addEventListener('click', function() {
                    this.classList.add('opacity-75', 'cursor-wait');
                    setTimeout(() => {
                        this.classList.remove('opacity-75', 'cursor-wait');
                    }, 500);
                });
            });
        });

        // Add intersection observer for animation triggers
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // Observe all cards and sections
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.bg-white, .hover\\:shadow-lg').forEach(el => {
                observer.observe(el);
            });
        });
    </script>

    <!-- Custom Styles -->
    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-in-left {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slide-in-right {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
        }

        .animate-slide-in-left {
            animation: slide-in-left 0.7s ease-out forwards;
        }

        .animate-slide-in-right {
            animation: slide-in-right 0.8s ease-out forwards;
        }

        /* Custom scrollbar for better UX */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            @apply bg-slate-300 dark:bg-slate-600 rounded-full;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            @apply bg-slate-400 dark:bg-slate-500;
        }

        /* Smooth transitions */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Enhanced hover effects */
        .card-hover:hover {
            transform: translateY(-2px);
        }

        /* Focus styles */
        button:focus-visible,
        a:focus-visible {
            @apply outline-2 outline-offset-2 outline-blue-500;
        }

        /* Enhanced section spacing */
        .section-spacing > * + * {
            margin-top: 2.5rem;
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            
            .card-hover:hover {
                transform: none;
            }
        }
    </style>

</div>