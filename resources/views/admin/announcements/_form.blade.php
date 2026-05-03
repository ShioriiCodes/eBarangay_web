@php
    /** @var \App\Models\Announcement|null $announcement */
    $announcement = $announcement ?? null;
@endphp

<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Title</label>
        <input name="title" value="{{ old('title', $announcement?->title) }}" required class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Content</label>
        <textarea name="content" rows="8" required class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">{{ old('content', $announcement?->content) }}</textarea>
        @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Category</label>
        <input name="category" value="{{ old('category', $announcement?->category) }}" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
        @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Priority</label>
        <select name="priority" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
            @foreach (['normal', 'important', 'urgent'] as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $announcement?->priority ?? 'normal') === $priority)>{{ ucfirst($priority) }}</option>
            @endforeach
        </select>
        @error('priority') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
        <select name="status" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
            @foreach (['draft', 'published', 'archived'] as $status)
                <option value="{{ $status }}" @selected(old('status', $announcement?->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Published date (optional)</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($announcement?->published_at)?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
        @error('published_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Expiration date (optional)</label>
        <input type="date" name="expires_at" value="{{ old('expires_at', optional($announcement?->expires_at)?->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
        @error('expires_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
