@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-lg border-slate-300 shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]']) }}>
