<aside
    class="bg-navy-900 text-white flex-shrink-0 transition-all duration-300 shadow-2xl z-20 relative border-r border-gold-500/20"
    :class="sidebarOpen ? 'w-72' : 'w-20'">

    <button @click="sidebarOpen = !sidebarOpen"
        class="hidden md:flex absolute -right-3 top-10 bg-gold-500 text-navy-950 rounded-full p-1.5 shadow-lg hover:scale-110 transition border border-navy-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            :class="{ 'rotate-180': !sidebarOpen }">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <div class="p-6 text-center border-b border-slate-800 overflow-hidden">
        <div class="inline-block p-2 rounded-full border-2 border-gold-500 shadow-lg shadow-gold-500/20"
            :class="sidebarOpen ? 'mb-4' : 'mb-0'">
            <span class="text-gold-500 font-classic text-xl font-bold">ME</span>
        </div>
        <div x-show="sidebarOpen" x-cloak>
            <h1 class="font-classic text-lg text-gold-500 font-bold tracking-widest uppercase">Mater</h1>
            <p class="text-[10px] text-slate-400 uppercase tracking-[0.4em]">Educatrix</p>
        </div>
    </div>

    <nav class="mt-4 px-4 space-y-2">
        <a href="{{ route('dashboard') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-gold-500 text-navy-950 font-bold shadow-lg shadow-gold-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Dashboard</span>
        </a>

        <div class="pt-4 pb-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 px-3"
            x-show="sidebarOpen">
            Gestão Acadêmica
        </div>

        <a href="{{ route('classrooms.index') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('classrooms.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Turmas</span>
        </a>

        <a href="{{ route('students.index') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('students.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Alunos</span>
        </a>

        <a href="{{ route('subjects.index') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('subjects.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Disciplinas</span>
        </a>

        <a href="{{ route('evaluations.index') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('evaluations.*') ? 'bg-gold-500 text-navy-950 font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Avaliações</span>
        </a>

        <div class="pt-4 pb-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 px-3"
            x-show="sidebarOpen">
            Configurações
        </div>

        <a href="#"
            class="flex items-center p-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-gold-500 transition-all">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Sistema</span>
        </a>
    </nav>
</aside>
