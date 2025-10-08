<div class="space-y-8 p-6 max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                Dashboard Overview
            </h1>
            <p class="text-base-content/70 mt-1 text-lg">Monitor your document management system performance</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button 
                icon="o-arrow-path" 
                class="btn-outline btn-sm hover:btn-primary transition-colors duration-300"
                wire:click="refreshDashboard" 
                tooltip="Refresh data" />
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="transform hover:scale-105 transition-all duration-500 h-full relative z-10 group">
            <x-stat
                title="Total Documents"
                value="{{ number_format($totalDocuments) }}"
                icon="o-document-text"
                tooltip-bottom="Total documents in system"
                class="h-24 bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 dark:from-blue-900/30 dark:via-blue-800/25 dark:to-blue-700/20 border-blue-200 dark:border-blue-700 shadow-lg hover:shadow-2xl hover:shadow-blue-200/50 flex flex-col justify-center hover:z-50 group-hover:border-blue-300 transition-all duration-500" />
        </div>
        
        <div class="transform hover:scale-105 transition-all duration-500 h-full relative z-10 group">
            <x-stat
                title="Active Documents"
                description="Currently valid"
                value="{{ number_format($activeDocuments) }}"
                icon="o-check-circle"
                tooltip-bottom="Documents currently in effect"
                class="h-24 bg-gradient-to-br from-green-50 via-green-100 to-green-200 dark:from-green-900/30 dark:via-green-800/25 dark:to-green-700/20 border-green-200 dark:border-green-700 shadow-lg hover:shadow-2xl hover:shadow-green-200/50 flex flex-col justify-center hover:z-50 group-hover:border-green-300 transition-all duration-500" />
        </div>
        
        <div class="transform hover:scale-105 transition-all duration-500 h-full relative z-10 group">
            <x-stat
                title="Expired Documents"
                description="Past effective date"
                value="{{ number_format($expiredDocuments) }}"
                icon="o-x-circle"
                tooltip-bottom="Documents that have expired"
                class="h-24 bg-gradient-to-br from-red-50 via-red-100 to-red-200 dark:from-red-900/30 dark:via-red-800/25 dark:to-red-700/20 border-red-200 dark:border-red-700 shadow-lg hover:shadow-2xl hover:shadow-red-200/50 flex flex-col justify-center hover:z-50 group-hover:border-red-300 transition-all duration-500" />
        </div>
        
        <div class="transform hover:scale-105 transition-all duration-500 h-full relative z-10 group">
            <x-stat
                title="Recently Added"
                description="Last 7 days"
                value="{{ number_format($recentlyAddedCount) }}"
                icon="o-plus-circle"
                tooltip-bottom="Documents added in the past week"
                class="h-24 bg-gradient-to-br from-purple-50 via-purple-100 to-purple-200 dark:from-purple-900/30 dark:via-purple-800/25 dark:to-purple-700/20 border-purple-200 dark:border-purple-700 shadow-lg hover:shadow-2xl hover:shadow-purple-200/50 flex flex-col justify-center hover:z-50 group-hover:border-purple-300 transition-all duration-500" />
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @if(count($documentsChart) > 0)
            <div class="transform hover:scale-[1.02] transition-all duration-500 group">
                <x-card title="Documents by Type" class="h-96 shadow-lg hover:shadow-2xl border-0 bg-gradient-to-br from-white via-gray-50/50 to-blue-50/30 dark:from-gray-800 dark:via-gray-850 dark:to-gray-900 group-hover:from-blue-50/50 group-hover:to-indigo-50/50 transition-all duration-500">
                    <x-slot:menu>
                        <div class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors duration-300">
                            <x-icon name="o-chart-pie" class="w-5 h-5 text-primary group-hover:scale-110 transition-transform duration-300" />
                        </div>
                    </x-slot:menu>
                    
                    <div class="h-72 w-full p-4">
                        <x-chart wire:model="documentsChart" class="w-full h-64" />
                    </div>
                </x-card>
            </div>
        @endif
        
        @if(count($statusChart) > 0)
            <div class="transform hover:scale-[1.02] transition-all duration-500 group">
                <x-card title="Document Status" class="h-96 shadow-lg hover:shadow-2xl border-0 bg-gradient-to-br from-white via-gray-50/50 to-green-50/30 dark:from-gray-800 dark:via-gray-850 dark:to-gray-900 group-hover:from-green-50/50 group-hover:to-emerald-50/50 transition-all duration-500">
                    <x-slot:menu>
                        <div class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors duration-300">
                            <x-icon name="o-chart-bar" class="w-5 h-5 text-primary group-hover:scale-110 transition-transform duration-300" />
                        </div>
                    </x-slot:menu>
                    
                    <div class="h-72 w-full p-4">
                        <x-chart wire:model="statusChart" class="w-full h-64" />
                    </div>
                </x-card>
            </div>
        @endif
        
        {{-- Show placeholder if no charts --}}
        @if(count($documentsChart) == 0 && count($statusChart) == 0)
            <div class="lg:col-span-2">
                <x-card title="Analytics" class="h-96 shadow-lg border-0 bg-gradient-to-br from-white via-gray-50/50 to-gray-100/30 dark:from-gray-800 dark:via-gray-850 dark:to-gray-900">
                    <div class="flex items-center justify-center h-80 text-base-content/60">
                        <div class="text-center animate-pulse">
                            <div class="relative mb-6">
                                <x-icon name="o-chart-bar" class="w-16 h-16 mx-auto opacity-30 animate-bounce" />
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-primary/20 rounded-full animate-ping"></div>
                            </div>
                            <p class="text-lg font-medium">No data available</p>
                            <p class="text-sm">Charts will appear once documents are added to the system</p>
                        </div>
                    </div>
                </x-card>
            </div>
        @endif
    </div>

    {{-- Recent Documents Section --}}
    <div class="transform hover:scale-[1.005] transition-all duration-500">
        <x-collapse class="bg-base-100 shadow-lg hover:shadow-2xl border-0 rounded-xl overflow-hidden backdrop-blur-sm" wire:model="showRecentDocuments">
            <x-slot:heading>
                <div class="flex items-center justify-between w-full p-4 transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-all duration-300">
                            <x-icon name="o-document-text" class="w-5 h-5 text-primary group-hover:scale-110 transition-transform duration-300" />
                        </div>
                        <div>
                            <span class="font-semibold text-lg text-base-content">Recent Documents</span>
                            <span class="text-sm text-base-content/60 ml-2">Published in the last 7 days</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-badge value="{{ count($newDocuments) }}" class="badge-primary shadow-sm" />
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                @if(count($newDocuments) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6">
                        @foreach($newDocuments as $document)
                            <div class="group p-6 rounded-xl border border-base-200 hover:border-primary/50 hover:shadow-xl transition-all duration-500 cursor-pointer bg-gradient-to-br from-white via-gray-50/50 to-primary/5 dark:from-gray-800 dark:via-gray-850 dark:to-gray-900 hover:from-primary/10 hover:via-primary/5 hover:to-secondary/10 hover:scale-[1.02] backdrop-blur-sm"
                                 wire:click="viewDocument('{{ $document['id'] }}')">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-primary group-hover:text-primary-focus transition-colors duration-300 text-base group-hover:scale-105 transform origin-left">
                                            {{ $document['number'] }}
                                        </h4>
                                        <p class="text-sm text-base-content/80 mt-2 line-clamp-2 leading-relaxed group-hover:text-base-content transition-colors duration-300">
                                            {{ $document['title'] }}
                                        </p>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        @if($document['days_ago'] == 0 && $document['hours_ago'] < 1)
                                            <x-badge value="Just now" class="badge-success badge-sm shadow-sm animate-pulse" />
                                        @elseif($document['days_ago'] < 1)
                                            <x-badge value="{{ $document['hours_ago'] }}h ago" class="badge-info badge-sm shadow-sm group-hover:animate-pulse" />
                                        @elseif($document['days_ago'] == 1)
                                            <x-badge value="Yesterday" class="badge-info badge-sm shadow-sm" />
                                        @else
                                            <x-badge value="{{ $document['days_ago'] }}d ago" class="badge-ghost badge-sm" />
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between pt-4 border-t border-base-200/50 group-hover:border-primary/30 transition-colors duration-300">
                                    <div class="flex items-center gap-4 text-xs text-base-content/60 group-hover:text-base-content/80 transition-colors duration-300">
                                        <div class="flex items-center gap-1">
                                            <x-icon name="o-document-text" class="w-3 h-3 group-hover:scale-110 transition-transform duration-300" />
                                            <span>{{ $document['type'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <x-icon name="o-calendar" class="w-3 h-3 group-hover:scale-110 transition-transform duration-300" />
                                            <span>{{ $document['formatted_published_date'] }}</span>
                                        </div>
                                    </div>
                                    
                                    <x-badge 
                                        value="{{ $document['status'] }}"
                                        @class([
                                            'badge-xs shadow-sm group-hover:shadow-md transition-shadow duration-300',
                                            'badge-success' => $document['status'] === 'Berlaku',
                                            'badge-error' => $document['status'] === 'Tidak Berlaku',
                                        ]) />
                                </div>
                                
                                {{-- Hover overlay effect --}}
                                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary/5 to-secondary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 text-center p-6 border-t border-base-200/50">
                        <x-button 
                            label="View All Recent Documents" 
                            link="{{ route('documents.index') }}?filter_published_start={{ now()->subDays(7)->toDateString() }}&filter_published_end={{ now()->toDateString() }}"
                            class="btn-outline btn-sm hover:scale-105 hover:btn-primary transition-all duration-300 shadow-sm hover:shadow-md"
                            icon="o-arrow-right"
                            spinner />
                    </div>
                @else
                    <div class="flex items-center justify-center h-48 text-base-content/60 p-6">
                        <div class="text-center">
                            <x-icon name="o-document-text" class="w-12 h-12 mx-auto mb-3 opacity-30" />
                            <p class="font-medium">No recent documents</p>
                            <p class="text-sm">No documents published in the last 7 days</p>
                        </div>
                    </div>
                @endif
            </x-slot:content>
        </x-collapse>
    </div>

    {{-- Expiration Alerts Section --}}
    <div class="transform hover:scale-[1.005] transition-all duration-500">
        <x-collapse class="bg-base-100 shadow-lg hover:shadow-2xl border-0 rounded-xl overflow-hidden backdrop-blur-sm" wire:model="showExpirationAlerts">
            <x-slot:heading>
                <div class="flex items-center justify-between w-full p-4 transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-warning/10 rounded-lg group-hover:bg-warning/20 transition-all duration-300">
                            <x-icon name="o-exclamation-triangle" class="w-5 h-5 text-warning group-hover:scale-110 transition-transform duration-300" />
                        </div>
                        <div>
                            <span class="font-semibold text-lg text-base-content">Expiration Alerts</span>
                            <span class="text-sm text-base-content/60 ml-2">Documents expiring within 7 days</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if(count($nearExpiredDocuments) > 0)
                            <x-badge value="{{ count($nearExpiredDocuments) }}" class="badge-warning animate-pulse shadow-sm" />
                        @else
                            <x-badge value="0" class="badge-ghost shadow-sm" />
                        @endif
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                @if(count($nearExpiredDocuments) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6">
                        @foreach($nearExpiredDocuments as $document)
                            <div class="group p-6 rounded-xl border transition-all duration-500 cursor-pointer hover:shadow-xl hover:scale-[1.02] backdrop-blur-sm
                                        {{ $document['is_critical'] ? 'border-red-200 bg-gradient-to-br from-red-50 via-red-100/50 to-red-200/30 hover:shadow-red-200/50 hover:border-red-300' : 'border-amber-200 bg-gradient-to-br from-amber-50 via-amber-100/50 to-amber-200/30 hover:shadow-amber-200/50 hover:border-amber-300' }}"
                                 wire:click="viewDocument('{{ $document['id'] }}')">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h4 class="font-semibold group-hover:scale-105 transition-transform duration-300 {{ $document['is_critical'] ? 'text-red-700' : 'text-amber-700' }} text-base">
                                            {{ $document['number'] }}
                                        </h4>
                                        <p class="text-sm text-base-content/80 mt-2 line-clamp-2 leading-relaxed group-hover:text-base-content transition-colors duration-300">
                                            {{ $document['title'] }}
                                        </p>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        @if($document['days_left'] <= 0)
                                            <x-badge value="EXPIRED" class="badge-error badge-sm animate-pulse shadow-lg" />
                                        @elseif($document['days_left'] < 1)
                                            <x-badge 
                                                value="{{ $document['hours_left'] }}h left" 
                                                class="badge-error badge-sm animate-pulse shadow-lg" />
                                        @else
                                            <x-badge 
                                                value="{{ $document['days_left'] }}d left" 
                                                class="badge-warning badge-sm shadow-sm group-hover:animate-pulse" />
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between pt-4 border-t border-base-200/50 group-hover:border-opacity-70 transition-all duration-300">
                                    <div class="flex items-center gap-4 text-xs text-base-content/60 group-hover:text-base-content/80 transition-colors duration-300">
                                        <div class="flex items-center gap-1">
                                            <x-icon name="o-document-text" class="w-3 h-3 group-hover:scale-110 transition-transform duration-300" />
                                            <span>{{ $document['type'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <x-icon name="o-clock" class="w-3 h-3 group-hover:scale-110 transition-transform duration-300" />
                                            <span>Until {{ $document['formatted_effective_until'] }}</span>
                                        </div>
                                    </div>
                                    
                                    @if($document['is_critical'])
                                        <div class="flex items-center gap-1 text-red-600 text-xs">
                                            <x-icon name="o-exclamation-triangle" class="w-4 h-4 animate-pulse group-hover:scale-110 transition-transform duration-300" />
                                            <span class="font-semibold">URGENT</span>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Urgent pulse overlay for critical documents --}}
                                @if($document['is_critical'])
                                    <div class="absolute inset-0 rounded-xl bg-red-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none animate-pulse"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 text-center p-6 border-t border-base-200/50">
                        <x-button 
                            label="View All Expiring Documents" 
                            link="{{ route('documents.index') }}?filter_effective_start={{ now()->toDateString() }}&filter_effective_end={{ now()->addDays(7)->toDateString() }}"
                            class="btn-outline btn-warning btn-sm hover:scale-105 hover:btn-warning transition-all duration-300 shadow-sm hover:shadow-md"
                            icon="o-clock" 
                            spinner />
                    </div>
                @else
                    <div class="flex items-center justify-center h-48 text-base-content/60 p-6">
                        <div class="text-center">
                            <x-icon name="o-check-circle" class="w-12 h-12 mx-auto mb-3 text-success opacity-50" />
                            <p class="font-medium text-success">All documents are safe</p>
                            <p class="text-sm">No documents expiring in the next 7 days</p>
                        </div>
                    </div>
                @endif
            </x-slot:content>
        </x-collapse>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

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
    
    .group:hover .group-hover\:scale-110 {
        transition: transform 0.3s ease-in-out;
    }
    
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
    }
</style>
@endpush
