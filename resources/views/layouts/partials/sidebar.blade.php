<aside
    class="bg-navy-900 text-white flex-shrink-0 transition-all duration-300 shadow-2xl z-20 relative border-r border-gold-500/20 h-screen flex flex-col"
    :class="sidebarOpen ? 'w-72' : 'w-20'">

    {{-- Botão de Toggle da Sidebar --}}
    <button @click="sidebarOpen = !sidebarOpen"
        class="hidden md:flex absolute -right-3 top-10 bg-gold-500 text-navy-950 rounded-full p-1.5 shadow-lg hover:scale-110 transition border border-navy-900 z-30">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            :class="{ 'rotate-180': !sidebarOpen }">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    {{-- CABEÇALHO (Logo Fixa no Topo) --}}
    <div class="p-6 text-center border-b border-slate-200/10 overflow-hidden transition-all flex-shrink-0">
        <div class="inline-block transition-all duration-300"
            :class="sidebarOpen ? 'mb-3 w-16 h-16' : 'mb-0 w-10 h-10'">
            <img src="{{ asset('img/logo.png') }}" alt="Mater Educatrix"
                class="w-full h-full object-contain filter drop-shadow-[0_4px_6px_rgba(212,175,55,0.2)]">
        </div>

        <div x-show="sidebarOpen" x-cloak>
            <h1 class="font-classic text-lg text-gold-500 font-bold tracking-widest uppercase">Mater</h1>
            <p class="text-[10px] text-slate-400 uppercase tracking-[0.4em]">Educatrix</p>
        </div>
    </div>

    {{-- MENU DE NAVEGAÇÃO (Com Scroll Habilitado) --}}
    <nav class="mt-4 px-4 space-y-2 flex-1 overflow-y-auto custom-scrollbar pb-6">
        <a href="{{ route('dashboard') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-gold-500 text-navy-950 font-bold shadow-lg shadow-gold-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Dashboard</span>
        </a>

        {{-- SEÇÃO: Gestão Acadêmica --}}
        <div class="pt-4 pb-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 px-3"
            x-show="sidebarOpen">
            Gestão Acadêmica
        </div>

        <a href="{{ route('students.index') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('students.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Alunos</span>
        </a>

        <a href="{{ route('attendance.index') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('attendance.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Diário de Classe</span>
        </a>

        <a href="{{ route('classrooms.index') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('classrooms.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Turmas</span>
        </a>

        @can('admin')
            <a href="{{ route('subjects.index') }}"
                class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('subjects.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span x-show="sidebarOpen" class="ml-3">Disciplinas</span>
            </a>
        @endcan

        <a href="{{ route('evaluations.index') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('evaluations.*') ? 'bg-gold-500 text-navy-950 font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Avaliações</span>
        </a>

        {{-- SEÇÃO: Biblioteca --}}
        <div class="pt-4 pb-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 px-3"
            x-show="sidebarOpen">
            Biblioteca
        </div>

        <a href="{{ route('books.index') }}"
            class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('books.*') ? 'bg-gold-500 text-navy-950 font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3">Acervo (Livros)</span>
        </a>

        @can('admin')
            {{-- SEÇÃO: Configurações --}}
            <div class="pt-4 pb-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 px-3"
                x-show="sidebarOpen">
                Gestão Administrativa
            </div>

            <!-- Ano Letivo & Bimestre -->
            <a href="{{ route('settings.school') }}"
                class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('settings.*') ? 'bg-gold-500 text-navy-950 font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-show="sidebarOpen" class="ml-3">
                    Ano Letivo & Bimestre
                </span>
            </a>

            <!-- Link para Listagem de Ocorrências (Padronizado) -->
            <a href="{{ route('occurrences.index') }}"
                class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('occurrences.*') ? 'bg-gold-500 text-navy-950 font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span x-show="sidebarOpen" class="ml-3">Ocorrências</span>
            </a>

            <!-- Tipos de Ocorrência -->
            <a href="{{ route('occurrence-types.index') }}"
                class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('occurrence-types.*') ? 'bg-gold-500 text-navy-950 font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01M12 8v2m0 2h.01" />
                </svg>
                <span x-show="sidebarOpen" class="ml-3">Tipos de Ocorrência</span>
            </a>

            <!-- Usuários -->
            <a href="{{ route('users.index') }}"
                class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('users.*') ? 'bg-gold-500 text-navy-950 font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span x-show="sidebarOpen" class="ml-3">Usuários</span>
            </a>

            <!-- Gerar Calendário -->
            <a href="{{ route('admin.calendar.index') }}"
                class="flex items-center p-3 rounded-xl transition-all {{ request()->routeIs('admin.calendar.*') ? 'bg-gold-500 text-navy-950 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-gold-500' }}">
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span x-show="sidebarOpen" class="ml-3">Gerar Calendário</span>
            </a>
        @endcan

        <hr class="border-navy-800 my-4 opacity-50" />


        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="m-0">
            @csrf
            <button type="submit"
                class="w-full flex items-center p-3 rounded-xl transition-all text-slate-500 hover:bg-rose-950/30 hover:text-rose-400 group cursor-pointer">
                <svg class="w-5 h-5 min-w-[20px] transition-colors group-hover:text-rose-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span x-show="sidebarOpen"
                    class="ml-3 text-xs font-bold uppercase tracking-wider transition-opacity duration-200">
                    Sair do Sistema
                </span>
            </button>
        </form>
    </nav>
</aside>
