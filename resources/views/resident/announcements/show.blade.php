<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-[#0038A8]">Announcement</h2>
            <a href="{{ route('resident.announcements.index') }}" class="text-sm font-semibold text-[#0038A8] hover:underline">← Back</a>
        </div>
    </x-slot>

    @php
        $priorityClasses = match ($announcement->priority) {
            'urgent' => 'bg-red-100 text-red-800',
            'important' => 'bg-amber-100 text-amber-800',
            default => 'bg-slate-100 text-slate-700',
        };
    @endphp

    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center gap-2">
            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#0038A8]">{{ $announcement->category ?: 'General' }}</span>
            <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $priorityClasses }}">{{ $announcement->priority }}</span>
        </div>
        <h1 class="mt-4 text-2xl font-bold text-slate-900">{{ $announcement->title }}</h1>
        <p class="mt-2 text-sm text-slate-500">Published {{ $announcement->published_at?->format('M d, Y h:i A') ?? '—' }}</p>
        <div class="prose prose-slate mt-6 max-w-none text-slate-800">
            {!! nl2br(e($announcement->content)) !!}
        </div>
    </article>
</x-app-layout>
