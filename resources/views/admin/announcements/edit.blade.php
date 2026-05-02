<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-[#0038A8]">Edit Announcement</h2>
            <a href="{{ route('admin.announcements.index') }}" class="text-sm font-semibold text-[#0038A8] hover:underline">← Back</a>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if (session('success'))
            <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="space-y-5">
                @csrf
                @method('PATCH')
                @include('admin.announcements._form', ['announcement' => $announcement])
                <button type="submit" class="rounded-xl bg-[#0038A8] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">Update</button>
            </form>
        </div>

        <div class="flex flex-wrap gap-3">
            @if ($announcement->status !== 'archived')
                <form method="POST" action="{{ route('admin.announcements.archive', $announcement) }}" onsubmit="return confirm('Archive this announcement?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Archive</button>
                </form>
            @endif
            @if (auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete permanently?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-xl border border-red-200 px-5 py-2.5 text-sm font-semibold text-[#CE1126] hover:bg-red-50">Delete</button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
