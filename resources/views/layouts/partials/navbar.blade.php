<header class="h-20 bg-white border-b border-slate-100 flex items-center justify-between px-4 md:px-8 transition-all">
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

        {{-- <button @click="darkMode = !darkMode"
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
        </button> --}}

        <div class="flex items-center pl-4 border-l border-slate-100">
            <button type="button" onclick="openProfileModal()"
                class="w-10 h-10 rounded-xl bg-navy-900 border border-gold-500/30 flex items-center justify-center font-black text-gold-500 hover:border-gold-500 hover:scale-105 transition-all cursor-pointer focus:outline-none"
                title="Editar Meu Perfil">
                {{-- Pega a inicial do nome do usuário --}}
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </button>
        </div>


    </div>

    <!-- Modal Editar Perfil e Senha -->
    <div id="modal-edit-profile"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="modal-profile-content"
            class="relative w-full max-w-lg bg-[#0f172a] border border-slate-800 rounded-2xl shadow-2xl transition-all">

            <!-- Cabeçalho -->
            <div class="flex items-center justify-between p-6 border-b border-slate-800">
                <h3 class="text-sm font-black uppercase tracking-widest text-gold-500">Editar Perfil & Senha</h3>
                <button type="button" onclick="closeProfileModal()"
                    class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Formulário -->
            <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                <!-- Nome -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-300 mb-2">Nome
                        Completo</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                        class="w-full bg-[#0b1329] border @error('name') border-red-500 @else border-slate-800 @enderror rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-gold-500">
                    @error('name')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- E-mail -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-300 mb-2">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                        class="w-full bg-[#0b1329] border @error('email') border-red-500 @else border-slate-800 @enderror rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-gold-500">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Seção Alterar Senha -->
                <div class="pt-4 border-t border-slate-800">
                    <p class="text-xs font-bold text-slate-400 mb-3 uppercase tracking-wider">Alterar Senha (Opcional)
                    </p>

                    <!-- Senha Atual -->
                    <div class="mb-3">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-300 mb-1">Senha
                            Atual</label>
                        <input type="password" name="current_password" placeholder="••••••••"
                            class="w-full bg-[#0b1329] border @error('current_password') border-red-500 @else border-slate-800 @enderror rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-gold-500">
                        @error('current_password')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nova Senha -->
                    <div class="mb-3">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-300 mb-1">Nova
                            Senha</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full bg-[#0b1329] border @error('password') border-red-500 @else border-slate-800 @enderror rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-gold-500">
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirmar Nova Senha -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-300 mb-1">Confirmar
                            Nova Senha</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                            class="w-full bg-[#0b1329] border border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-gold-500">
                    </div>
                </div>

                <!-- Rodapé -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" onclick="closeProfileModal()"
                        class="px-5 py-2.5 text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-white rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-gold-500 to-amber-600 hover:from-gold-600 hover:to-amber-700 text-slate-950 font-black uppercase tracking-widest text-xs rounded-xl shadow-lg transition-all">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Controladores de Estado do Modal -->
    <script>
        function openProfileModal() {
            const modal = document.getElementById('modal-edit-profile');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeProfileModal() {
            const modal = document.getElementById('modal-edit-profile');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Reabre o modal automaticamente se houver qualquer erro de validação vindo do Laravel
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                openProfileModal();
            @endif
        });
    </script>

</header>
