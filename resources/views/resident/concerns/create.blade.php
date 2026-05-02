<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-[#0038A8]">Submit Concern</h2></x-slot>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('resident.concerns.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Subject</label>
                <input name="subject" class="w-full rounded-xl border-slate-300 focus:border-[#0038A8] focus:ring-[#0038A8]" value="{{ old('subject') }}">
                @error('subject') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Message</label>
                <textarea name="message" rows="4" class="w-full rounded-xl border-slate-300 focus:border-[#0038A8] focus:ring-[#0038A8]">{{ old('message') }}</textarea>
                @error('message') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600">
                Image evidence upload placeholder: this will be enabled in a future update once concern attachments are finalized.
            </div>
            <button class="rounded-xl bg-[#0038A8] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#CE1126]">Submit Concern</button>
        </form>
    </div>
</x-app-layout>
