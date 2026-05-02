<section
    x-data="{
        activeSlide: 0,
        slides: [
            { code: '01 - REQUESTS', title: 'Online Document Requests', description: 'Submit barangay clearance, residency, indigency, and barangay ID requests without visiting the office first.' },
            { code: '02 - TRACKING', title: 'Request Status Tracking', description: 'Track requests from pending to approved in real-time so residents always know the current processing stage.' },
            { code: '03 - CONCERNS', title: 'Resident Concern Submission', description: 'Send complaints or community concerns digitally and receive updates from barangay staff in one portal.' },
            { code: '04 - NOTIFICATIONS', title: 'Real-Time Notifications', description: 'Get timely alerts for review updates, claim schedules, and important barangay announcements.' }
        ],
        init() {
            setInterval(() => {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            }, 3200);
        }
    }"
    class="relative h-full min-h-[26rem] w-full overflow-hidden bg-gradient-to-br from-blue-800 via-blue-900 to-blue-950 px-6 py-10 text-white sm:px-10 md:min-h-0 md:h-full"
>
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -right-16 top-6 h-72 w-72 rounded-full border border-white/20 opacity-40"></div>
        <div class="absolute -left-20 bottom-0 h-80 w-80 rounded-full bg-indigo-500/35 blur-3xl"></div>
    </div>

    <div class="relative mx-auto flex h-full w-full max-w-xl flex-col justify-between gap-16 md:gap-0">
        <div>
            <div class="inline-flex items-center gap-3 rounded-2xl bg-white px-4 py-2.5 shadow-sm shadow-blue-950/20">
                <div class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#0038A8] text-xs font-bold text-white">eB</div>
                <span class="text-sm font-bold tracking-tight text-slate-900">eBarangay</span>
            </div>

            <h1 class="mt-8 text-4xl font-bold leading-tight tracking-tight sm:text-[2.5rem]">Barangay services, organized</h1>
            <p class="mt-4 max-w-md text-sm leading-relaxed text-blue-100/95 sm:text-base">
                Secure, role-based resident records with quick search and transparent request tracking.
            </p>
        </div>

        <div>
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.35em] text-blue-200/90" x-text="slides[activeSlide].code"></p>
            <h2 class="mt-3 text-2xl font-bold leading-tight sm:text-3xl transition-all duration-300" x-text="slides[activeSlide].title"></h2>
            <p class="mt-3 max-w-md text-sm leading-relaxed text-blue-100/90 transition-all duration-300" x-text="slides[activeSlide].description"></p>

            <div class="mt-8 flex items-center justify-center gap-2">
                <template x-for="(slide, idx) in slides" :key="idx">
                    <button
                        type="button"
                        @click="activeSlide = idx"
                        class="rounded-full transition-all duration-300"
                        :class="activeSlide === idx ? 'h-2.5 w-7 bg-white' : 'h-2.5 w-2.5 bg-white/50 hover:bg-white/70'"
                        :aria-label="'Show slide ' + (idx + 1)"
                    ></button>
                </template>
            </div>
        </div>
    </div>
</section>
