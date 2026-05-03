<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-lg border border-transparent bg-[#CE1126] px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 hover:bg-[#b80f22] focus:outline-none focus:ring-2 focus:ring-[#CE1126] focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
