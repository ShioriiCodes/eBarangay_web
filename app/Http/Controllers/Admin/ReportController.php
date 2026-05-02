<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Concern;
use App\Models\DocumentRequest;
use App\Support\DocumentRequestCatalog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ReportController extends Controller
{
    private const DOCUMENT_STATUSES = [
        'pending',
        'under_review',
        'approved',
        'ready_for_printing',
        'ready_for_claiming',
        'completed',
        'rejected',
    ];

    private const CONCERN_STATUSES = ['pending', 'reviewing', 'resolved', 'rejected'];

    public function index(Request $request): View|RedirectResponse
    {
        $rules = [
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ];
        if ($request->filled('from') && $request->filled('to')) {
            $rules['to'] = ['nullable', 'date', 'after_or_equal:from'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        [$from, $to] = $this->resolveDateRange($request);

        if ($from->gt($to)) {
            return redirect()->back()->withInput()->withErrors(['to' => 'The end date must be on or after the start date.']);
        }

        $requestQuery = DocumentRequest::query()->whereBetween('created_at', [$from, $to], 'and', false);
        $concernQuery = Concern::query()->whereBetween('created_at', [$from, $to], 'and', false);

        $announcementQuery = Announcement::query()->whereBetween('created_at', [$from, $to], 'and', false);

        $publishedInRange = Announcement::query()
            ->where('status', 'published')
            ->where(function ($q) use ($from, $to): void {
                $q->whereBetween('published_at', [$from, $to], 'and', false)
                    ->orWhere(function ($q2) use ($from, $to): void {
                        $q2->whereNull('published_at')
                            ->whereBetween('created_at', [$from, $to], 'and', false);
                    });
            });

        $stats = [
            'total_residents' => User::query()->where('role', 'resident')->where('status', 'active')->count(),
            'total_requests' => (clone $requestQuery)->count(),
            'pending_requests' => (clone $requestQuery)->whereIn('status', ['pending', 'under_review'])->count(),
            'approved_requests' => (clone $requestQuery)->whereIn('status', ['approved', 'ready_for_printing', 'ready_for_claiming'])->count(),
            'completed_requests' => (clone $requestQuery)->where('status', 'completed')->count(),
            'rejected_requests' => (clone $requestQuery)->where('status', 'rejected')->count(),
            'total_concerns' => (clone $concernQuery)->count(),
            'resolved_concerns' => (clone $concernQuery)->where('status', 'resolved')->count(),
            'published_announcements' => (clone $publishedInRange)->count(),
        ];

        $announcementBreakdown = [
            'draft' => (clone $announcementQuery)->where('status', 'draft')->count(),
            'published' => (clone $publishedInRange)->count(),
            'archived' => (clone $announcementQuery)->where('status', 'archived')->count(),
            'urgent' => (clone $announcementQuery)->where('priority', 'urgent')->where('status', 'published')->count(),
        ];

        $requestsByStatus = $this->countsByColumn((clone $requestQuery), 'status', self::DOCUMENT_STATUSES);
        $requestsByType = $this->countsByColumn(
            (clone $requestQuery),
            'document_type',
            ['barangay_clearance', 'certificate_of_indigency', 'certificate_of_residency', 'barangay_id']
        );

        $concernsByStatus = $this->countsByColumn((clone $concernQuery), 'status', self::CONCERN_STATUSES);

        $monthKeys = $this->monthKeysBetween($from, $to);
        $monthlyRequests = $this->monthlyCounts(DocumentRequest::class, $from, $to, $monthKeys);
        $monthlyConcerns = $this->monthlyCounts(Concern::class, $from, $to, $monthKeys);

        $recentRequests = DocumentRequest::query()
            ->whereBetween('created_at', [$from, $to], 'and', false)
            ->latest()
            ->limit(8)
            ->get(['id', 'request_number', 'document_type', 'status', 'created_at']);

        $recentConcerns = Concern::query()
            ->whereBetween('created_at', [$from, $to], 'and', false)
            ->latest()
            ->limit(8)
            ->get(['id', 'concern_number', 'subject', 'status', 'created_at']);

        $activityLogs = ActivityLog::query()
            ->whereBetween('created_at', [$from, $to], 'and', false)
            ->with('user:id,name')
            ->latest()
            ->limit(30)
            ->get();

        $chartRequestStatus = [
            'labels' => $this->documentStatusLabels($requestsByStatus->keys()->all()),
            'data' => $requestsByStatus->values()->all(),
        ];

        $chartConcernStatus = [
            'labels' => $this->concernStatusLabels($concernsByStatus->keys()->all()),
            'data' => $concernsByStatus->values()->all(),
        ];

        $chartMonthlyTrend = [
            'labels' => collect($monthKeys)->map(fn (string $ym) => Carbon::createFromFormat('Y-m', $ym)->format('M Y'))->all(),
            'requests' => collect($monthKeys)->map(fn (string $k) => $monthlyRequests[$k] ?? 0)->all(),
            'concerns' => collect($monthKeys)->map(fn (string $k) => $monthlyConcerns[$k] ?? 0)->all(),
        ];

        $documentTypeLabels = DocumentRequestCatalog::allTypeLabels();

        $documentStatusLabelsMap = [
            'pending' => 'Pending',
            'under_review' => 'Under review',
            'approved' => 'Approved',
            'ready_for_printing' => 'Ready for printing',
            'ready_for_claiming' => 'Ready for claiming',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
        ];

        $concernStatusLabelsMap = [
            'pending' => 'Pending',
            'reviewing' => 'Reviewing',
            'resolved' => 'Resolved',
            'rejected' => 'Rejected',
        ];

        return view('admin.reports', [
            'stats' => $stats,
            'announcementBreakdown' => $announcementBreakdown,
            'documentTypeLabels' => $documentTypeLabels,
            'documentStatusLabelsMap' => $documentStatusLabelsMap,
            'concernStatusLabelsMap' => $concernStatusLabelsMap,
            'requestsByStatus' => $requestsByStatus,
            'requestsByType' => $requestsByType,
            'concernsByStatus' => $concernsByStatus,
            'monthlyRequests' => $monthlyRequests,
            'monthlyConcerns' => $monthlyConcerns,
            'monthKeys' => $monthKeys,
            'recentRequests' => $recentRequests,
            'recentConcerns' => $recentConcerns,
            'activityLogs' => $activityLogs,
            'chartRequestStatus' => $chartRequestStatus,
            'chartConcernStatus' => $chartConcernStatus,
            'chartMonthlyTrend' => $chartMonthlyTrend,
            'filters' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
            ],
            'isStaff' => $request->user()?->role === 'staff',
        ]);
    }

    public function exportPdfPlaceholder(Request $request): RedirectResponse
    {
        return redirect()
            ->route('admin.reports.index', $request->only(['from', 'to']))
            ->with('info', 'PDF export is coming soon.');
    }

    public function exportExcelPlaceholder(Request $request): RedirectResponse
    {
        return redirect()
            ->route('admin.reports.index', $request->only(['from', 'to']))
            ->with('info', 'Excel export is coming soon.');
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolveDateRange(Request $request): array
    {
        if (! $request->filled('from') && ! $request->filled('to')) {
            return [now()->startOfMonth()->startOfDay(), now()->endOfMonth()->endOfDay()];
        }

        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : Carbon::parse($request->input('to'))->startOfMonth()->startOfDay();

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : Carbon::parse($request->input('from'))->endOfMonth()->endOfDay();

        return [$from, $to];
    }

    /**
     * @return list<string>
     */
    private function monthKeysBetween(Carbon $from, Carbon $to): array
    {
        $keys = [];
        $cursor = $from->copy()->startOfMonth();
        $end = $to->copy()->startOfMonth();

        while ($cursor <= $end) {
            $keys[] = $cursor->format('Y-m');
            $cursor->addMonth();
        }

        return $keys;
    }

    /**
     * @param  class-string<DocumentRequest|Concern>  $modelClass
     * @param  list<string>  $keys
     * @return array<string, int>
     */
    private function monthlyCounts(string $modelClass, Carbon $from, Carbon $to, array $keys): array
    {
        $rows = $modelClass::query()
            ->whereBetween('created_at', [$from, $to], 'and', false)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym")
            ->selectRaw('COUNT(*) as c')
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('c', 'ym');

        $out = [];
        foreach ($keys as $k) {
            $out[$k] = (int) ($rows[$k] ?? 0);
        }

        return $out;
    }

    /**
     * @param  list<string>  $orderedKeys
     * @return Collection<string, int>
     */
    private function countsByColumn($query, string $column, array $orderedKeys): Collection
    {
        $counts = (clone $query)
            ->toBase()
            ->selectRaw('`'.$column.'` as k')
            ->selectRaw('COUNT(*) as c')
            ->groupBy($column)
            ->pluck('c', 'k');

        return collect($orderedKeys)->mapWithKeys(fn (string $key) => [$key => (int) ($counts[$key] ?? 0)]);
    }

    /**
     * @param  list<string>  $keys
     * @return list<string>
     */
    private function documentStatusLabels(array $keys): array
    {
        $map = [
            'pending' => 'Pending',
            'under_review' => 'Under review',
            'approved' => 'Approved',
            'ready_for_printing' => 'Ready for printing',
            'ready_for_claiming' => 'Ready for claiming',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
        ];

        return array_map(fn (string $k) => $map[$k] ?? $k, $keys);
    }

    /**
     * @param  list<string>  $keys
     * @return list<string>
     */
    private function concernStatusLabels(array $keys): array
    {
        $map = [
            'pending' => 'Pending',
            'reviewing' => 'Reviewing',
            'resolved' => 'Resolved',
            'rejected' => 'Rejected',
        ];

        return array_map(fn (string $k) => $map[$k] ?? $k, $keys);
    }
}
