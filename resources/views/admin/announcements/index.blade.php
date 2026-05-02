<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-[#0038A8]">Announcements</h2>
            <a href="{{ route('admin.announcements.create') }}" class="rounded-xl bg-[#0038A8] px-4 py-2 text-sm font-semibold text-white hover:bg-[#002f8d]">New announcement</a>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if (session('success'))
            <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">{{ session('success') }}</div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.announcements.index') }}" class="grid gap-4 md:grid-cols-12">
                <div class="md:col-span-5">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
                    <input name="q" value="{{ $filters['q'] }}" placeholder="Title or content" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                </div>
                <div class="md:col-span-3">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <select name="status" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                        <option value="">All</option>
                        @foreach (['draft', 'published', 'archived'] as $status)
                            <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Priority</label>
                    <select name="priority" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                        <option value="">All</option>
                        @foreach (['normal', 'important', 'urgent'] as $priority)
                            <option value="{{ $priority }}" @selected($filters['priority'] === $priority)>{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end md:col-span-1">
                    <button type="submit" class="w-full rounded-xl bg-[#0038A8] px-3 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">Go</button>
                </div>
            </form>
        </section>

        <section class="hidden rounded-2xl border border-slate-200 bg-white shadow-sm md:block">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-left text-sm">
                    <thead class="border-b border-slate-200 text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Priority</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Published</th>
                            <th class="px-4 py-3">Expires</th>
                            <th class="px-4 py-3">Created By</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($announcements as $announcement)
                            @php
                                $priorityClasses = match ($announcement->priority) {
                                    'urgent' => 'bg-red-100 text-red-800',
                                    'important' => 'bg-amber-100 text-amber-800',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                                $statusClasses = match ($announcement->status) {
                                    'published' => 'bg-green-100 text-green-800',
                                    'archived' => 'bg-slate-200 text-slate-800',
                                    default => 'bg-blue-100 text-blue-800',
                                };
                            @endphp
                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $announcement->title }}</td>
                                <td class="px-4 py-3">{{ $announcement->category ?: '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $priorityClasses }}">{{ $announcement->priority }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $statusClasses }}">{{ $announcement->status }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $announcement->published_at?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $announcement->expires_at?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $announcement->creator?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="rounded-lg border border-[#0038A8] px-3 py-1.5 text-xs font-semibold text-[#0038A8] hover:bg-[#0038A8] hover:text-white">Edit</a>
                                        @if ($announcement->status !== 'archived')
                                            <form method="POST" action="{{ route('admin.announcements.archive', $announcement) }}" onsubmit="return confirm('Archive this announcement?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Archive</button>
                                            </form>
                                        @endif
                                        @if (auth()->user()->role === 'admin')
                                            <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-[#CE1126] hover:bg-red-50">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-slate-500">No announcements yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 px-4 py-3">{{ $announcements->links() }}</div>
        </section>

        <section class="space-y-3 md:hidden">
            @forelse ($announcements as $announcement)
                @php
                    $priorityClasses = match ($announcement->priority) {
                        'urgent' => 'bg-red-100 text-red-800',
                        'important' => 'bg-amber-100 text-amber-800',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-2">
                        <p class="font-semibold text-slate-900">{{ $announcement->title }}</p>
                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $priorityClasses }}">{{ $announcement->priority }}</span>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Status: <span class="font-semibold text-slate-700">{{ $announcement->status }}</span></p>
                    <p class="mt-1 text-xs text-slate-500">Published: {{ $announcement->published_at?->format('M d, Y') ?? '—' }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="rounded-lg border border-[#0038A8] px-3 py-1.5 text-xs font-semibold text-[#0038A8]">Edit</a>
                        @if ($announcement->status !== 'archived')
                            <form method="POST" action="{{ route('admin.announcements.archive', $announcement) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700">Archive</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center text-sm text-slate-500">No announcements yet.</div>
            @endforelse
            <div>{{ $announcements->links() }}</div>
        </section>
    </div>
</x-app-layout>
