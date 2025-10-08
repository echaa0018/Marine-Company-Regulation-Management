<div>
      <div class="flex justify-end mb-6">
          <x-button
              label="Add New Document"
              icon="o-plus"
              link="{{ route('document.create') }}"
              class="btn-primary shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300"
              spinner />
      </div>

      {{-- Large Centered Search Section --}}
      <div class="flex justify-center items-center py-8">
          <div class="w-full max-w-2xl px-6">
              <div class="text-center mb-6">
                  <h2 class="text-4xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent mb-3">
                      Search Documents
                  </h2>
                  <p class="text-base-content/70 text-lg">Find regulations, decisions, and circulars - results update as you type</p>
              </div>

              <div class="relative group">
                  <x-input
                      placeholder="Start typing to search documents..."
                      wire:model.live.debounce.300ms="search"
                      icon="o-magnifying-glass"
                      class="input-lg text-lg shadow-lg group-focus-within:shadow-xl transition-shadow duration-300 border-2 group-focus-within:border-primary/50"
                      clearable />

                  <div wire:loading wire:target="search" class="absolute top-1/2 right-14 -translate-y-1/2">
                      <x-loading class="loading-spinner loading-sm text-primary" />
                  </div>
                  
                  {{-- Search glow effect --}}
                  <div class="absolute inset-0 rounded-lg bg-gradient-to-r from-primary/20 to-secondary/20 opacity-0 group-focus-within:opacity-20 transition-opacity duration-300 pointer-events-none"></div>
              </div>

              @if($search)
                  <div class="mt-4 text-center animate-fade-in">
                      <x-badge
                          value="Searched: {{ $search }}"
                          class="badge-info gap-2 shadow-sm hover:shadow-md transition-shadow duration-300"
                          dismissible
                          wire:click="$set('search', '')" />
                  </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
    }
    
    .group:hover .group-hover\:scale-110 {
        transition: transform 0.3s ease-in-out;
    }
    
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
</style>
@endpush      {{-- Filter --}}
      @if($filterType || $filterConfidentiality || $filterStatus || $filterPublishedStart || $filterPublishedEnd || $filterEffectiveStart || $filterEffectiveEnd)
          <div class="flex justify-end mb-2">
              <x-button
                  label="Clear All Filters"
                  wire:click="clearFilters"
                  class="btn-ghost btn-sm text-error hover:text-error hover:bg-error/10 transition-all duration-300"
                  icon="o-x-mark" />
          </div>
      @endif

      <x-collapse class="hover:shadow-xl transition-shadow duration-300 rounded-m   ">
          <x-slot:heading>
              <div class="flex items-center gap-2 p-4 transition-all duration-300">
                  <div class="p-2 bg-primary/10 rounded-lg">
                      <x-icon name="o-funnel" class="w-5 h-5 text-primary" />
                  </div>
                  <span class="font-medium">Filter Documents</span>
                  @if($filterType || $filterConfidentiality || $filterStatus || $filterPublishedStart || $filterPublishedEnd || $filterEffectiveStart || $filterEffectiveEnd)
                      <x-badge value="{{ collect([$filterType, $filterConfidentiality, $filterStatus, $filterPublishedStart, $filterPublishedEnd, $filterEffectiveStart, $filterEffectiveEnd])->filter()->count() }}" class="badge-primary badge-sm animate-pulse" />
                  @endif
              </div>
          </x-slot:heading>
          <x-slot:content>
              <div class="space-y-6 p-6 from-base-50 to-base-100 dark:from-base-200 dark:to-base-300">
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                      <div class="group">
                          <x-select
                              label="Document Type"
                              wire:model.live="filterType"
                              :options="$typeOptions"
                              placeholder="All Types"
                              placeholder-value=""
                              icon="o-document-text"
                              class="group-hover:border-primary/50 transition-colors duration-300"
                          />
                      </div>

                      <div class="group">
                          <x-select
                              label="Status"
                              wire:model.live="filterStatus"
                              :options="$statusOptions"
                              placeholder="All Status"
                              placeholder-value=""
                              icon="o-check-circle"
                              class="group-hover:border-primary/50 transition-colors duration-300"
                          />
                      </div>

                      <div class="group">
                          <x-select
                              label="Confidentiality"
                              wire:model.live="filterConfidentiality"
                              :options="$confidentialityOptions"
                              placeholder="All Levels"
                              placeholder-value=""
                              icon="o-shield-check"
                              class="group-hover:border-primary/50 transition-colors duration-300"
                          />
                      </div>
                  </div>

                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                      <x-card class="p-4 transition-all duration-300">
                          <div class="flex items-center gap-2 mb-3">
                              <div class="p-1 bg-primary/20 rounded">
                                  <x-icon name="o-calendar-days" class="w-5 h-5 text-primary" />
                              </div>
                              <h4 class="font-semibold text-sm">Published Date Range</h4>
                          </div>
                          <div class="grid grid-cols-2 gap-3">
                              <x-datetime
                                  label="From"
                                  wire:model.live="filterPublishedStart"
                                  icon="o-calendar"
                                  type="date"
                                  class="hover:border-primary/50 transition-colors duration-300"
                              />
                              <x-datetime
                                  label="To"
                                  wire:model.live="filterPublishedEnd"
                                  icon="o-calendar"
                                  type="date"
                                  class="hover:border-primary/50 transition-colors duration-300"
                              />
                          </div>
                      </x-card>

                      <x-card class="p-4 transition-all duration-300">
                          <div class="flex items-center gap-2 mb-3">
                              <div class="p-1 bg-warning/20 rounded">
                                  <x-icon name="o-clock" class="w-5 h-5 text-warning" />
                              </div>
                              <h4 class="font-semibold text-sm">Effective Until Range</h4>
                          </div>
                          <div class="grid grid-cols-2 gap-3">
                              <x-datetime
                                  label="From"
                                  wire:model.live="filterEffectiveStart"
                                  icon="o-calendar"
                                  type="date"
                                  class="hover:border-warning/50 transition-colors duration-300"
                              />
                              <x-datetime
                                  label="To"
                                  wire:model.live="filterEffectiveEnd"
                                  icon="o-calendar"
                                  type="date"
                                  class="hover:border-warning/50 transition-colors duration-300"
                              />
                          </div>
                      </x-card>
                  </div>

                  @if($filterType || $filterConfidentiality || $filterStatus || $filterPublishedStart || $filterPublishedEnd || $filterEffectiveStart || $filterEffectiveEnd)
                      <div class="pt-4 border-t border-base-300">
                          <div class="flex items-center gap-2 mb-3">
                              <div class="p-1 bg-info/20 rounded">
                                  <x-icon name="o-tag" class="w-4 h-4 text-info" />
                              </div>
                              <span class="text-sm font-medium">Active Filters</span>
                          </div>
                          <div class="flex flex-wrap gap-2">
                              @if($filterType)
                                  <x-badge
                                      value="Type: {{ $filterType }}"
                                      class="badge-info gap-1 hover:shadow-md transition-shadow duration-300"
                                      dismissible
                                      wire:click="$set('filterType', '')" />
                              @endif
                              @if($filterStatus)
                                  <x-badge
                                      value="Status: {{ $filterStatus }}"
                                      class="badge-success gap-1 hover:shadow-md transition-shadow duration-300"
                                      dismissible
                                      wire:click="$set('filterStatus', '')" />
                              @endif
                              @if($filterConfidentiality)
                                  <x-badge
                                      value="Confidentiality: {{ $filterConfidentiality }}"
                                      class="badge-warning gap-1 hover:shadow-md transition-shadow duration-300"
                                      dismissible
                                      wire:click="$set('filterConfidentiality', '')" />
                              @endif
                              @if($filterPublishedStart || $filterPublishedEnd)
                                  <x-badge
                                      value="Published: {{ $filterPublishedStart ? \Carbon\Carbon::parse($filterPublishedStart)->format('M d, Y') : 'Any' }} - {{ $filterPublishedEnd ? \Carbon\Carbon::parse($filterPublishedEnd)->format('M d, Y') : 'Any' }}"
                                      class="badge-primary gap-1 hover:shadow-md transition-shadow duration-300"
                                      dismissible
                                      wire:click="$set('filterPublishedStart', ''); $set('filterPublishedEnd', '')" />
                              @endif
                              @if($filterEffectiveStart || $filterEffectiveEnd)
                                  <x-badge
                                      value="Effective: {{ $filterEffectiveStart ? \Carbon\Carbon::parse($filterEffectiveStart)->format('M d, Y') : 'Any' }} - {{ $filterEffectiveEnd ? \Carbon\Carbon::parse($filterEffectiveEnd)->format('M d, Y') : 'Any' }}"
                                      class="badge-secondary gap-1 hover:shadow-md transition-shadow duration-300"
                                      dismissible
                                      wire:click="$set('filterEffectiveStart', ''); $set('filterEffectiveEnd', '')" />
                              @endif
                          </div>
                      </div>
                  @endif
              </div>

              <div wire:loading wire:target="filterType,filterConfidentiality,filterStatus,filterPublishedStart,filterPublishedEnd,filterEffectiveStart,filterEffectiveEnd" class="mt-4">
                  <div class="flex items-center justify-center gap-3 p-4 bg-gradient-to-r from-info/10 to-primary/10 rounded-lg border border-info/20 backdrop-blur-sm">
                      <x-loading class="loading-dots loading-md text-info" />
                      <span class="text-sm font-medium text-info">Filtering documents...</span>
                  </div>
              </div>
          </x-slot:content>
      </x-collapse>

      {{-- Documents Count --}}
      {{-- <div class="flex justify-between items-center mb-6 mt-6 p-4 bg-gradient-to-r from-base-50 to-base-100 dark:from-base-200 dark:to-base-300 rounded-lg border border-base-200 shadow-sm">
          <div class="flex items-center gap-3">
              <div class="p-2 bg-primary/10 rounded-lg">
                  <x-icon name="o-document-text" class="w-5 h-5 text-primary" />
              </div>
              <div class="text-sm text-base-content/70">
                  @if($search || $filterType || $filterConfidentiality || $filterStatus || $filterPublishedStart || $filterPublishedEnd || $filterEffectiveStart || $filterEffectiveEnd)
                      <span class="font-semibold text-base-content text-lg">{{ $documents->count() }}</span> of {{ $documents->total() }} documents
                      @if($search)
                          matching <span class="font-medium bg-primary/10 px-2 py-1 rounded text-primary">"{{ $search }}"</span>
                      @endif
                      @if($filterType || $filterConfidentiality || $filterStatus || $filterPublishedStart || $filterPublishedEnd || $filterEffectiveStart || $filterEffectiveEnd)
                          with <span class="font-medium text-info">filters applied</span>
                      @endif
                  @else
                      <span class="font-semibold text-base-content text-lg">{{ $documents->count() }}</span> of {{ $documents->total() }} documents
                  @endif
                  @if($documents->total() > 0)
                      <span class="text-base-content/50 block text-xs mt-1">
                          Showing {{ (($documents->currentPage() - 1) * $documents->perPage()) + 1 }} - {{ min($documents->currentPage() * $documents->perPage(), $documents->total()) }}
                      </span>
                  @endif
              </div>
          </div>
          @if($documents->hasPages())
              <div class="text-sm text-base-content/50 flex items-center gap-2 bg-base-100 px-3 py-2 rounded-lg border">
                  <x-icon name="o-document-duplicate" class="w-4 h-4" />
                  Page {{ $documents->currentPage() }} of {{ $documents->lastPage() }}
              </div>
          @endif
      </div> --}}

    {{-- Document Cards Grid --}}
    <div class="relative">
        <div wire:loading wire:target="filterType,filterConfidentiality,filterStatus,filterPublishedStart,filterPublishedEnd,filterEffectiveStart,filterEffectiveEnd"
             class="absolute inset-0 bg-base-100/90 backdrop-blur-sm z-10 flex items-center justify-center rounded-xl">
            <div class="text-center p-8 bg-white/80 rounded-xl shadow-lg">
                <x-loading class="loading-dots loading-lg text-primary mb-3" />
                <p class="text-sm text-base-content/70 font-medium">Filtering documents...</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-6">
        @forelse($documents as $document)
            {{-- RECTANGULAR HORIZONTAL CARD DESIGN --}}
            <div wire:key="document-{{ $document['id'] }}"
                 wire:click="viewDetail('{{ $document['id'] }}')"
                 class="bg-white dark:bg-gray-800 rounded-lg border border-base-300 hover:shadow-lg hover:border-primary/50 transform hover:scale-[1.01] transition-all duration-300 cursor-pointer group p-4 relative h-36 flex">

                {{-- Left Section - Main Info --}}
                <div class="flex-grow flex flex-col justify-between pr-3 min-h-0">
                    {{-- Header Row --}}
                    <div class="flex items-start justify-between mb-2">
                        <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded text-xs font-medium">
                            {{ $document['type'] }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <div class="flex-grow mb-2 min-h-0">
                        <h3 class="font-semibold text-base-content text-sm leading-tight group-hover:text-primary transition-colors duration-300 line-clamp-2" title="{{ $document['title'] }}">
                            {{ Str::limit($document['title'], 80, '...') }}
                        </h3>
                    </div>
                    
                    {{-- Document Number --}}
                    <p class="text-xs text-base-content/60 font-mono bg-base-50 dark:bg-base-200 px-2 py-1 rounded flex-shrink-0">
                        {{ $document['number'] }}
                    </p>
                </div>

                {{-- Right Section - Status & Details --}}
                <div class="flex flex-col justify-between items-end w-32 pl-3">
                    {{-- Status & Confidentiality --}}
                    <div class="flex flex-col items-end gap-1">
                        {{-- Status Badge --}}
                        @if($document['status'] === 'Berlaku')
                            <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded text-xs font-medium">
                                Active
                            </span>
                        @elseif($document['status'] === 'Tidak Berlaku')
                            <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded text-xs font-medium">
                                Inactive
                            </span>
                        @else
                            <span class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 rounded text-xs font-medium">
                                {{ Str::limit($document['status'], 8) }}
                            </span>
                        @endif

                        {{-- Confidentiality Icon --}}
                        <div class="flex items-center gap-1">
                            @if(($document['confidentiality'] ?? 'Internal Use') === 'Confidential')
                                <span class="text-red-600 text-xs" title="Confidential">🔒</span>
                                <span class="text-xs text-red-600">Conf.</span>
                            @elseif(($document['confidentiality'] ?? 'Internal Use') === 'Internal Use')
                                <span class="text-orange-600 text-xs" title="Internal Use">🏢</span>
                                <span class="text-xs text-orange-600">Int.</span>
                            @else
                                <span class="text-blue-600 text-xs" title="{{ $document['confidentiality'] ?? 'Internal Use' }}">📄</span>
                                <span class="text-xs text-blue-600">{{ Str::limit($document['confidentiality'] ?? 'Internal Use', 6) }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Effective Until Date --}}
                    <div class="flex flex-col items-end text-xs text-base-content/70 bg-base-50 dark:bg-base-200 px-2 py-1 rounded">
                        <div class="flex items-center gap-1">
                            <x-icon name="o-clock" class="w-3 h-3" />
                            <span class="text-xs">Until</span>
                        </div>
                        <span class="font-medium text-base-content">{{ \Carbon\Carbon::parse($document['effective_until'])->format('M d, Y') }}</span>
                    </div>
                </div>

                {{-- Loading indicator for this specific card --}}
                @if($loadingDocumentId == $document['id'])
                    <div class="absolute inset-0 bg-base-100/90 flex items-center justify-center rounded-lg backdrop-blur-sm z-20">
                        <div class="flex items-center gap-2 bg-white/90 px-3 py-2 rounded shadow-lg">
                             <x-loading class="loading-spinner loading-sm text-primary" />
                             <span class="text-xs font-medium">Loading...</span>
                        </div>
                    </div>
                @endif
            </div>
        @empty
        {{-- Enhanced Empty State --}}
        <div class="col-span-full">
            <x-card class="p-12 bg-gradient-to-br from-white via-gray-50/50 to-base-100 dark:from-gray-800 dark:via-gray-850 dark:to-gray-900 border-0 shadow-lg">
                <div class="text-center">
                    <div class="relative mb-8">
                        <x-icon name="o-document-magnifying-glass" class="w-20 h-20 text-base-300 mx-auto animate-pulse" />
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-primary/20 rounded-full animate-ping"></div>
                    </div>
                    <h3 class="text-2xl font-semibold text-base-content mb-4">No documents found</h3>

                    @if($search || $filterType || $filterConfidentiality || $filterStatus || $filterPublishedStart || $filterPublishedEnd || $filterEffectiveStart || $filterEffectiveEnd)
                        <p class="text-base-content/70 mb-8 max-w-md mx-auto text-lg">
                            No documents match your current
                            @if($search)
                                search for <span class="font-medium bg-primary/10 px-2 py-1 rounded text-primary">"{{ $search }}"</span>
                                @if($filterType || $filterConfidentiality || $filterStatus || $filterPublishedStart || $filterPublishedEnd || $filterEffectiveStart || $filterEffectiveEnd)
                                    and applied filters
                                @endif
                            @else
                                filters
                            @endif
                        </p>
                        <div class="flex flex-wrap justify-center gap-3">
                            @if($search)
                                <x-button
                                    label="Clear Search"
                                    wire:click="$set('search', '')"
                                    class="btn-outline btn-sm hover:btn-primary hover:scale-105 transition-all duration-300 shadow-sm hover:shadow-md"
                                    icon="o-magnifying-glass-minus" />
                            @endif
                            @if($filterType || $filterConfidentiality || $filterStatus || $filterPublishedStart || $filterPublishedEnd || $filterEffectiveStart || $filterEffectiveEnd)
                                <x-button
                                    label="Clear Filters"
                                    wire:click="clearFilters"
                                    class="btn-outline btn-sm hover:btn-primary hover:scale-105 transition-all duration-300 shadow-sm hover:shadow-md"
                                    icon="o-funnel" />
                            @endif
                        </div>
                    @else
                        <p class="text-base-content/70 mb-8 max-w-md mx-auto text-lg">
                            Get started by creating your first document or uploading existing ones to build your catalog.
                        </p>
                        <x-button
                            label="Create First Document"
                            link="{{ route('document.create') }}"
                            class="btn-primary hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl"
                            icon="o-plus" />
                    @endif
                </div>
            </x-card>
        </div>
        @endforelse
        </div>
    </div>

    {{-- Livewire Pagination --}}
    @if($documents->hasPages())
        <div class="mt-8">
            {{ $documents->links() }}
        </div>
    @endif
</div>