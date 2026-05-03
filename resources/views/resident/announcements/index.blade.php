<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0038A8]">Barangay Information Board</h2>
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('resident.announcements.index') }}" class="grid gap-4 md:grid-cols-12">
                <div class="md:col-span-6">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
                    <input name="q" value="{{ $filters['q'] }}" placeholder="Search by title" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                </div>
                <div class="md:col-span-3">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Category</label>
                    <select name="category" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                        <option value="">All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected($filters['category'] === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
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

        @if ($featured->isNotEmpty())
            <section>
                <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Featured</h3>
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ($featured as $announcement)
                        @php
                            $border = $announcement->priority === 'urgent'
                                ? 'border-[#CE1126]/40 bg-red-50/40'
                                : 'border-amber-200 bg-amber-50/40';
                        @endphp
                        <div class="rounded-2xl border {{ $border }} p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-[#0038A8]">{{ $announcement->category ?: 'General' }}</p>
                            <h4 class="mt-2 text-lg font-semibold text-slate-900">{{ $announcement->title }}</h4>
                            <p class="mt-2 line-clamp-3 text-sm text-slate-700">{{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 160) }}</p>
                            <div class="mt-4 flex items-center justify-between gap-2">
                                <span class="rounded-full bg-white/70 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-700">{{ $announcement->priority }}</span>
                                <a href="{{ route('resident.announcements.show', $announcement) }}" class="rounded-lg bg-[#0038A8] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#002f8d]">Read more</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($announcements as $announcement)
                @php
                    $priorityClasses = match ($announcement->priority) {
                        'urgent' => 'bg-red-100 text-red-800',
                        'important' => 'bg-amber-100 text-amber-800',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp
                <div class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-[#0038A8]">{{ $announcement->category ?: 'General' }}</p>
                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $priorityClasses }}">{{ $announcement->priority }}</span>
                    </div>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ $announcement->title }}</h3>
                    <p class="mt-2 flex-1 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 140) }}</p>
                    <p class="mt-3 text-xs text-slate-500">Published {{ $announcement->published_at?->format('M d, Y') ?? '—' }}</p>
                    <a href="{{ route('resident.announcements.show', $announcement) }}" class="mt-4 inline-flex w-full items-center justify-center rounded-xl border border-[#0038A8] px-4 py-2 text-sm font-semibold text-[#0038A8] hover:bg-[#0038A8] hover:text-white">Read more</a>
                </div>
            @empty
                <div class="sm:col-span-2 xl:col-span-3 rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500">
                    No announcements available right now.
                </div>
            @endforelse
        </section>

        <div>{{ $announcements->links() }}</div>
    </div>
</x-app-layout>
