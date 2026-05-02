<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-[#0038A8]">Reports &amp; analytics</h2>
                <p class="mt-1 text-sm text-slate-600">Service activity summary for the selected period.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.export.pdf', request()->only(['from', 'to'])) }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0038A8] hover:text-[#0038A8]">
                    Export PDF
                </a>
                <a href="{{ route('admin.reports.export.excel', request()->only(['from', 'to'])) }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0038A8] hover:text-[#0038A8]">
                    Export Excel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('info'))
            <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-[#0038A8]">
                {{ session('info') }}
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Date range</h3>
            <p class="mt-1 text-xs text-slate-500">Defaults to the current calendar month when no dates are chosen.</p>
            <form method="GET" action="{{ route('admin.reports.index') }}" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="from" class="mb-1 block text-sm font-medium text-slate-700">From</label>
                    <input id="from" type="date" name="from" value="{{ old('from', $filters['from']) }}"
                        class="w-full min-w-0 rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                </div>
                <div>
                    <label for="to" class="mb-1 block text-sm font-medium text-slate-700">To</label>
                    <input id="to" type="date" name="to" value="{{ old('to', $filters['to']) }}"
                        class="w-full min-w-0 rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                </div>
                <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-2">
                    <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-xl bg-[#0038A8] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#002f8d] focus:outline-none focus:ring-2 focus:ring-[#0038A8] focus:ring-offset-2 sm:flex-none sm:px-6">
                        Apply filter
                    </button>
                    <a href="{{ route('admin.reports.index') }}"
                        class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 sm:flex-none sm:px-6">
                        Reset
                    </a>
                </div>
            </form>
            @error('from')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('to')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <section class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3 xl:grid-cols-4">
            @php
                $kpis = [
                    ['label' => 'Total residents', 'value' => $stats['total_residents'], 'class' => 'text-slate-900', 'hint' => 'Active accounts'],
                    ['label' => 'Document requests', 'value' => $stats['total_requests'], 'class' => 'text-[#0038A8]', 'hint' => 'In period'],
                    ['label' => 'Pending requests', 'value' => $stats['pending_requests'], 'class' => 'text-amber-600', 'hint' => 'Pending + under review'],
                    ['label' => 'Approved pipeline', 'value' => $stats['approved_requests'], 'class' => 'text-[#0038A8]', 'hint' => 'Approved through ready to claim'],
                    ['label' => 'Completed requests', 'value' => $stats['completed_requests'], 'class' => 'text-emerald-600', 'hint' => 'In period'],
                    ['label' => 'Rejected requests', 'value' => $stats['rejected_requests'], 'class' => 'text-[#CE1126]', 'hint' => 'In period'],
                    ['label' => 'Total concerns', 'value' => $stats['total_concerns'], 'class' => 'text-slate-900', 'hint' => 'In period'],
                    ['label' => 'Resolved concerns', 'value' => $stats['resolved_concerns'], 'class' => 'text-emerald-600', 'hint' => 'In period'],
                    ['label' => 'Published announcements', 'value' => $stats['published_announcements'], 'class' => 'text-[#0038A8]', 'hint' => 'Published in period'],
                ];
            @endphp
            @foreach ($kpis as $kpi)
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $kpi['label'] }}</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums {{ $kpi['class'] }}">{{ number_format($kpi['value']) }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ $kpi['hint'] }}</p>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900">Document requests by status</h3>
                <div class="relative mx-auto mt-4 h-64 max-w-sm sm:h-72">
                    <canvas id="chartDocStatus"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900">Concerns by status</h3>
                <div class="relative mx-auto mt-4 h-64 max-w-sm sm:h-72">
                    <canvas id="chartConcernStatus"></canvas>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h3 class="text-lg font-semibold text-slate-900">Monthly trend</h3>
            <p class="mt-1 text-sm text-slate-500">Document requests vs concerns created per month (within the selected range).</p>
            <div class="relative mt-6 h-72 w-full min-w-0">
                <canvas id="chartMonthlyTrend"></canvas>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900">Requests by document type</h3>
                <ul class="mt-4 divide-y divide-slate-100">
                    @foreach ($requestsByType as $type => $count)
                        <li class="flex items-center justify-between py-3 text-sm">
                            <span class="text-slate-700">{{ $documentTypeLabels[$type] ?? $type }}</span>
                            <span class="font-semibold tabular-nums text-[#0038A8]">{{ number_format($count) }}</span>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-3 text-xs text-slate-400">Business clearance is not enabled in this deployment.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900">Announcements</h3>
                <ul class="mt-4 divide-y divide-slate-100">
                    <li class="flex items-center justify-between py-3 text-sm">
                        <span class="text-slate-700">Published <span class="text-slate-400">(in period)</span></span>
                        <span class="font-semibold tabular-nums text-[#0038A8]">{{ number_format($announcementBreakdown['published']) }}</span>
                    </li>
                    <li class="flex items-center justify-between py-3 text-sm">
                        <span class="text-slate-700">Draft <span class="text-slate-400">(created in period)</span></span>
                        <span class="font-semibold tabular-nums text-slate-800">{{ number_format($announcementBreakdown['draft']) }}</span>
                    </li>
                    <li class="flex items-center justify-between py-3 text-sm">
                        <span class="text-slate-700">Archived <span class="text-slate-400">(created in period)</span></span>
                        <span class="font-semibold tabular-nums text-slate-600">{{ number_format($announcementBreakdown['archived']) }}</span>
                    </li>
                    <li class="flex items-center justify-between py-3 text-sm">
                        <span class="text-slate-700">Urgent &amp; published</span>
                        <span class="font-semibold tabular-nums text-[#CE1126]">{{ number_format($announcementBreakdown['urgent']) }}</span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h3 class="text-lg font-semibold text-slate-900">Requests by status (detail)</h3>
            <div class="-mx-1 mt-4 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-3 py-2 font-medium">Status</th>
                            <th class="px-3 py-2 font-medium text-right">Count</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($requestsByStatus as $status => $count)
                            <tr>
                                <td class="px-3 py-2.5 text-slate-700">{{ $documentStatusLabelsMap[$status] ?? $status }}</td>
                                <td class="px-3 py-2.5 text-right font-semibold tabular-nums text-slate-900">{{ number_format($count) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h3 class="text-lg font-semibold text-slate-900">Concerns by status (detail)</h3>
            <p class="mt-1 text-xs text-slate-500">Statuses: pending, reviewing, resolved, rejected. Escalated is not used in this system.</p>
            <div class="-mx-1 mt-4 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-3 py-2 font-medium">Status</th>
                            <th class="px-3 py-2 font-medium text-right">Count</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($concernsByStatus as $status => $count)
                            <tr>
                                <td class="px-3 py-2.5 text-slate-700">{{ $concernStatusLabelsMap[$status] ?? $status }}</td>
                                <td class="px-3 py-2.5 text-right font-semibold tabular-nums text-slate-900">{{ number_format($count) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900">Monthly document requests</h3>
                <ul class="mt-4 max-h-72 space-y-2 overflow-y-auto text-sm">
                    @forelse ($monthKeys as $ym)
                        <li class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                            <span class="text-slate-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $ym)->format('M Y') }}</span>
                            <span class="font-semibold tabular-nums text-[#0038A8]">{{ number_format($monthlyRequests[$ym] ?? 0) }}</span>
                        </li>
                    @empty
                        <li class="text-slate-500">No months in range.</li>
                    @endforelse
                </ul>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900">Monthly concerns</h3>
                <ul class="mt-4 max-h-72 space-y-2 overflow-y-auto text-sm">
                    @forelse ($monthKeys as $ym)
                        <li class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                            <span class="text-slate-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $ym)->format('M Y') }}</span>
                            <span class="font-semibold tabular-nums text-[#CE1126]">{{ number_format($monthlyConcerns[$ym] ?? 0) }}</span>
                        </li>
                    @empty
                        <li class="text-slate-500">No months in range.</li>
                    @endforelse
                </ul>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900">Recent document requests</h3>
                <p class="mt-1 text-xs text-slate-500">Latest in this period (reference only; no sensitive payload).</p>
                <div class="-mx-1 mt-4 overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-3 py-2 font-medium">Ref</th>
                                <th class="px-3 py-2 font-medium">Type</th>
                                <th class="px-3 py-2 font-medium">Status</th>
                                <th class="px-3 py-2 font-medium">Submitted</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentRequests as $row)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-mono text-xs text-slate-800">{{ $row->request_number }}</td>
                                    <td class="px-3 py-2.5 text-slate-700">{{ $documentTypeLabels[$row->document_type] ?? $row->document_type }}</td>
                                    <td class="px-3 py-2.5 text-slate-600">{{ $documentStatusLabelsMap[$row->status] ?? $row->status }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-slate-500">{{ $row->created_at?->timezone(config('app.timezone'))->format('M j, Y g:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-6 text-center text-slate-500">No requests in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900">Recent concerns</h3>
                <div class="-mx-1 mt-4 overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-3 py-2 font-medium">Ref</th>
                                <th class="px-3 py-2 font-medium">Subject</th>
                                <th class="px-3 py-2 font-medium">Status</th>
                                <th class="px-3 py-2 font-medium">Submitted</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentConcerns as $row)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-mono text-xs text-slate-800">{{ $row->concern_number }}</td>
                                    <td class="max-w-[10rem] truncate px-3 py-2.5 text-slate-700" title="{{ $row->subject }}">{{ $row->subject }}</td>
                                    <td class="px-3 py-2.5 text-slate-600">{{ $concernStatusLabelsMap[$row->status] ?? $row->status }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-slate-500">{{ $row->created_at?->timezone(config('app.timezone'))->format('M j, Y g:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-6 text-center text-slate-500">No concerns in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h3 class="text-lg font-semibold text-slate-900">Latest activity</h3>
            <p class="mt-1 text-sm text-slate-500">System activity in the selected date range.</p>
            <div class="-mx-1 mt-4 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-3 py-2 font-medium">Action</th>
                            <th class="px-3 py-2 font-medium">Description</th>
                            <th class="px-3 py-2 font-medium">User</th>
                            <th class="px-3 py-2 font-medium">When</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($activityLogs as $log)
                            <tr>
                                <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-800">{{ $log->action }}</td>
                                <td class="max-w-xs truncate px-3 py-2.5 text-slate-600" title="{{ $log->description }}">{{ $log->description }}</td>
                                <td class="whitespace-nowrap px-3 py-2.5 text-slate-600">{{ $log->user?->name ?? '—' }}</td>
                                <td class="whitespace-nowrap px-3 py-2.5 text-slate-500">{{ $log->created_at?->timezone(config('app.timezone'))->format('M j, Y g:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-center text-slate-500">No activity in this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        @if ($isStaff)
            <section class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Staff access: full operational reports. System settings and destructive actions remain admin-only.
            </section>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" crossorigin="anonymous"></script>
        <script>
            (function () {
                const blue = '#0038A8';
                const red = '#CE1126';
                const amber = '#ca8a04';
                const green = '#16a34a';
                const slate = '#94a3b8';

                const docColors = [amber, slate, blue, '#2563eb', '#1e40af', green, red];
                const concernColors = [amber, blue, green, red];

                const docData = @json($chartRequestStatus);
                const concernData = @json($chartConcernStatus);
                const trendData = @json($chartMonthlyTrend);

                if (typeof Chart === 'undefined') return;

                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 10, font: { size: 11 } }
                        }
                    }
                };

                const ctxDoc = document.getElementById('chartDocStatus');
                if (ctxDoc) {
                    new Chart(ctxDoc, {
                        type: 'doughnut',
                        data: {
                            labels: docData.labels,
                            datasets: [{
                                data: docData.data,
                                backgroundColor: docColors,
                                borderWidth: 1,
                                borderColor: '#fff'
                            }]
                        },
                        options: { ...commonOptions }
                    });
                }

                const ctxCon = document.getElementById('chartConcernStatus');
                if (ctxCon) {
                    new Chart(ctxCon, {
                        type: 'doughnut',
                        data: {
                            labels: concernData.labels,
                            datasets: [{
                                data: concernData.data,
                                backgroundColor: concernColors,
                                borderWidth: 1,
                                borderColor: '#fff'
                            }]
                        },
                        options: { ...commonOptions }
                    });
                }

                const ctxTrend = document.getElementById('chartMonthlyTrend');
                if (ctxTrend) {
                    new Chart(ctxTrend, {
                        type: 'line',
                        data: {
                            labels: trendData.labels,
                            datasets: [
                                {
                                    label: 'Document requests',
                                    data: trendData.requests,
                                    borderColor: blue,
                                    backgroundColor: 'rgba(0, 56, 168, 0.08)',
                                    tension: 0.25,
                                    fill: true,
                                    pointRadius: 3
                                },
                                {
                                    label: 'Concerns',
                                    data: trendData.concerns,
                                    borderColor: red,
                                    backgroundColor: 'rgba(206, 17, 38, 0.06)',
                                    tension: 0.25,
                                    fill: true,
                                    pointRadius: 3
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            scales: {
                                x: {
                                    ticks: { maxRotation: 45, minRotation: 0, font: { size: 11 } },
                                    grid: { display: false }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 },
                                    grid: { color: 'rgba(148, 163, 184, 0.25)' }
                                }
                            },
                            plugins: {
                                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } }
                            }
                        }
                    });
                }
            })();
        </script>
    @endpush
</x-app-layout>
