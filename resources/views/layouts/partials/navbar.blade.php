<header
    class="h-20 bg-white border-b border-slate-100 flex items-center justify-between px-4 md:px-8 transition-all">
    <div class="flex items-center">
        <button @click="sidebarOpen = !sidebarOpen"
            class="block md:hidden p-2 mr-4 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        @php
            $settings = \App\Models\SchoolSetting::first();
        @endphp

        <div class="hidden sm:block">
            <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em]">
                Mater Educatrix
            </p>

            <h2 class="text-sm font-bold text-navy-900">
                Painel do Professor
            </h2>

            <div class="flex items-center gap-2 mt-1 flex-wrap">

                <span
                    class="px-2 py-1 rounded-lg bg-gold-500/10 text-gold-600 text-[10px] font-black uppercase tracking-widest border border-gold-500/20">
                    Ano Letivo:
                    {{ $settings?->current_year ?? date('Y') }}
                </span>

                <span
                    class="px-2 py-1 rounded-lg bg-navy-900/5 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-200">
                    {{ $settings?->active_bimester ?? 1 }}º Bimestre
                </span>

            </div>
        </div>
    </div>

    <div class="flex items-center space-x-2 md:space-x-6">
        {{-- <a href="{{ route('evaluations.store') }}"
            class="hidden md:flex items-center bg-gold-500 text-navy-950 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
            Nova Prova
        </a> --}}

        <button @click="darkMode = !darkMode"
            class="p-2.5 bg-slate-50 rounded-xl text-slate-500 border border-slate-200 hover:bg-slate-100 transition-colors focus:outline-none">

            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>

            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" x-cloak>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
            </svg>
        </button>

        <div class="flex items-center pl-4 border-l border-slate-100">
            <div
                class="w-10 h-10 rounded-xl bg-navy-900 border border-gold-500/30 flex items-center justify-center font-black text-gold-500">
                AD
            </div>
        </div>
    </div>
</header>
