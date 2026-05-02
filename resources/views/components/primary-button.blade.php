<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-lg bg-[#0038A8] px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 hover:bg-[#002f8d] focus:outline-none focus:ring-2 focus:ring-[#0038A8] focus:ring-offset-2 active:bg-[#022a77]']) }}>
    {{ $slot }}
</button>
