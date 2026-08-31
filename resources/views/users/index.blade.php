{{-- Formulário Rápido de Cadastro / Edição --}}
<div id="form-usuario"
    class="p-6 mb-6 bg-navy-950 text-white border border-slate-200 rounded-2xl {{ $errors->any() || $isEdit ? '' : 'hidden' }}">

    @include('partials.messages.errors')

    <form action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}" method="POST"
        class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        {{-- Campo: Nome Completo --}}
        <div>
            <label class="block text-xs uppercase font-bold text-gold-500 mb-1">Nome Completo</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="Ex: João Silva"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-amber-500 transition-all">
        </div>

        {{-- Campo: E-mail --}}
        <div>
            <label class="block text-xs uppercase font-bold text-gold-500 mb-1">E-mail</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                placeholder="Ex: joao@email.com" required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-sm font-mono outline-none focus:border-amber-500 transition-all">
        </div>

        {{-- Campo: Função / Perfil --}}
        <div>
            <label class="block text-xs uppercase font-bold text-gold-500 mb-1">Função / Perfil</label>
            <select name="role" required
                class="w-full bg-navy-900 border border-white/20 text-white rounded-xl px-4 py-2.5 text-sm outline-none focus:border-amber-500 transition-all">
                <option value="" disabled {{ !isset($user) ? 'selected' : '' }}>Selecione a Função</option>
                <option value="admin"
                    {{ old('role', $user->role->value ?? ($user->role ?? '')) == 'admin' ? 'selected' : '' }}>
                    Administrador</option>
                <option value="teacher"
                    {{ old('role', $user->role->value ?? ($user->role ?? '')) == 'teacher' ? 'selected' : '' }}>
                    Professor</option>
                <option value="preceptor"
                    {{ old('role', $user->role->value ?? ($user->role ?? '')) == 'preceptor' ? 'selected' : '' }}>
                    Preceptor</option>
            </select>
        </div>

        {{-- Campo: Senha com Botão de Visualização --}}
        <div x-data="{ showPassword: false }">
            <label class="block text-xs uppercase font-bold text-gold-500 mb-1">
                {{ $isEdit ? 'Nova Senha (opcional)' : 'Senha' }}
            </label>
            <div class="relative">
                <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="••••••••"
                    {{ $isEdit ? '' : 'required' }}
                    class="w-full bg-white/10 border border-white/20 rounded-xl pl-4 pr-10 py-2.5 text-sm outline-none focus:border-amber-500 transition-all">

                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white transition-colors">
                    {{-- Ícone Olho Aberto --}}
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{-- Ícone Olho Fechado --}}
                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.959 8.959 0 013.682-.782c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Campo: Confirmar Senha com Botão de Visualização (Apenas no Cadastro) --}}
        @if (!$isEdit)
            <div x-data="{ showPassword: false }">
                <label class="block text-xs uppercase font-bold text-gold-500 mb-1">Confirmar Senha</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" name="password_confirmation"
                        placeholder="••••••••" required
                        class="w-full bg-white/10 border border-white/20 rounded-xl pl-4 pr-10 py-2.5 text-sm outline-none focus:border-amber-500 transition-all">

                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white transition-colors">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.959 8.959 0 013.682-.782c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- Botão de Submissão ocupando a largura total (2 colunas) --}}
        <div class="md:col-span-2 flex justify-end pt-2">
            <button type="submit"
                class="bg-gold-500 text-navy-900 font-bold rounded-xl uppercase text-xs px-6 py-3 hover:bg-gold-600 transition-all shadow-md">
                {{ $isEdit ? 'Atualizar Usuário' : 'Gravar Usuário' }}
            </button>
        </div>
    </form>
</div>
