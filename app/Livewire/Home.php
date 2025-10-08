<?php

namespace App\Livewire;

use App\Models\Document;
use Livewire\Component;
use Mary\Traits\Toast;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Home extends Component
{
    use Toast;

    // Document-related properties
    public $newDocuments = [];
    public $nearExpiredDocuments = [];
    public $totalDocuments = 0;
    public $activeDocuments = 0;
    public $expiredDocuments = 0;
    public $recentlyAddedCount = 0;
    public array $documentsChart = [];
    public array $statusChart = [];
    
    // Collapse states
    public $showRecentDocuments = true;
    public $showExpirationAlerts = true;

    public function mount()
    {
        $this->loadStatistics();
        $this->loadNewDocuments();
        $this->loadNearExpiredDocuments();
        $this->loadCharts();
    }

    /**
     * Refresh all dashboard data
     */
    public function refreshDashboard()
    {
        $this->loadStatistics();
        $this->loadNewDocuments();
        $this->loadNearExpiredDocuments();
        $this->loadCharts();
        
        $this->success('Dashboard data refreshed successfully!');
    }

    public function render()
    {
        $breadcrumbs = [
            [
                'link' => route("home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("home"), // route('home') = nama route yang ada di web.php
                'label' => 'Dashboard', // label yang ditampilkan di breadcrumb
                // 'icon' => 's-dashboard',
            ],
        ];

        return view('livewire.home', [
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => 'Home',
        ]);
    }

    /**
     * Load document statistics
     */
    private function loadStatistics()
    {
        $this->totalDocuments = Document::count();
        $this->activeDocuments = Document::where('status', 'Berlaku')
            ->where('effective_until', '>=', now()->toDateString())
            ->count();
        $this->expiredDocuments = Document::where('status', 'Tidak Berlaku')
            ->orWhere('effective_until', '<', now()->toDateString())
            ->count();
        $this->recentlyAddedCount = Document::where('published_date', '>=', now()->subDays(7)->toDateString())
            ->count();
    }

    /**
     * Load new documents (published in last 7 days)
     */
    private function loadNewDocuments()
    {
        $this->newDocuments = Document::where('published_date', '>=', now()->subDays(7)->toDateString())
            ->orderBy('published_date', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($doc) {
                $daysAgo = round($doc->published_date->diffInDays(now()));
                $hoursAgo = 0;
                
                if ($daysAgo < 1) {
                    $hoursAgo = floor($doc->published_date->diffInHours(now()));
                }
                
                return [
                    'id' => $doc->id,
                    'number' => $doc->number,
                    'title' => $doc->title,
                    'type' => $doc->type,
                    'published_date' => $doc->published_date,
                    'formatted_published_date' => $doc->formatted_published_date,
                    'days_ago' => $daysAgo,
                    'hours_ago' => $hoursAgo,
                    'confidentiality' => $doc->confidentiality,
                    'status' => $doc->status,
                ];
            });
    }

    /**
     * Load near expired documents (expiring within 1 week)
     */
    private function loadNearExpiredDocuments()
    {
        $this->nearExpiredDocuments = Document::where('status', 'Berlaku')
            ->where('effective_until', '>=', now()->toDateString())
            ->where('effective_until', '<=', now()->addDays(7)->toDateString())
            ->orderBy('effective_until', 'asc')
            ->limit(6)
            ->get()
            ->map(function ($doc) {
                $daysLeft = round(now()->diffInDays($doc->effective_until, false));
                $hoursLeft = 0;
                
                if ($daysLeft < 1) {
                    $hoursLeft = floor(now()->diffInHours($doc->effective_until->endOfDay(), false));
                }
                
                return [
                    'id' => $doc->id,
                    'number' => $doc->number,
                    'title' => $doc->title,
                    'type' => $doc->type,
                    'effective_until' => $doc->effective_until,
                    'formatted_effective_until' => $doc->formatted_effective_until,
                    'days_left' => $daysLeft,
                    'hours_left' => $hoursLeft,
                    'confidentiality' => $doc->confidentiality,
                    'is_critical' => $daysLeft < 1,
                ];
            });
    }

    /**
     * Load charts data
     */
    private function loadCharts()
    {
        // Document type distribution
        $documentTypes = Document::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        if (!empty($documentTypes)) {
            $this->documentsChart = [
                'type' => 'doughnut',
                'data' => [
                    'labels' => array_keys($documentTypes),
                    'datasets' => [
                        [
                            'label' => 'Documents by Type',
                            'data' => array_values($documentTypes),
                            'backgroundColor' => [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                            ],
                        ]
                    ]
                ],
                'options' => [
                    'responsive' => true,
                    'maintainAspectRatio' => false,
                    'plugins' => [
                        'legend' => [
                            'position' => 'right',
                            'align' => 'center',
                            'labels' => [
                                'boxWidth' => 12,
                                'padding' => 15,
                                'usePointStyle' => true,
                            ]
                        ]
                    ],
                    'layout' => [
                        'padding' => [
                            'left' => 0,
                            'right' => 20,
                            'top' => 10,
                            'bottom' => 10
                        ]
                    ]
                ]
            ];
        }

        // Document status distribution
        if ($this->activeDocuments > 0 || $this->expiredDocuments > 0) {
            $this->statusChart = [
                'type' => 'pie',
                'data' => [
                    'labels' => ['Active', 'Expired'],
                    'datasets' => [
                        [
                            'label' => 'Document Status',
                            'data' => [$this->activeDocuments, $this->expiredDocuments],
                            'backgroundColor' => ['#10B981', '#EF4444'],
                        ]
                    ]
                ],
                'options' => [
                    'responsive' => true,
                    'maintainAspectRatio' => false,
                    'plugins' => [
                        'legend' => [
                            'position' => 'right',
                            'align' => 'center',
                            'labels' => [
                                'boxWidth' => 12,
                                'padding' => 15,
                                'usePointStyle' => true,
                            ]
                        ]
                    ],
                    'layout' => [
                        'padding' => [
                            'left' => 0,
                            'right' => 20,
                            'top' => 10,
                            'bottom' => 10
                        ]
                    ]
                ]
            ];
        }
    }

    /**
     * Navigate to document detail
     */
    public function viewDocument($documentId)
    {
        return redirect()->route('document.detail', $documentId);
    }
}
