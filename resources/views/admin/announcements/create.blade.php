<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-[#0038A8]">Create Announcement</h2>
            <a href="{{ route('admin.announcements.index') }}" class="text-sm font-semibold text-[#0038A8] hover:underline">← Back</a>
        </div>
    </x-slot>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-5">
            @csrf
            @include('admin.announcements._form', ['announcement' => null])
            <button type="submit" class="rounded-xl bg-[#0038A8] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">Save</button>
        </form>
    </div>
</x-app-layout>
