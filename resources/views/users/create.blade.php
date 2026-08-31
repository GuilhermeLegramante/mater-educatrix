@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex justify-between items-center border-b border-slate-200 pb-4">
            <div>
                <span class="text-gold-600 font-bold tracking-widest text-xs uppercase">Acessos</span>
                <h1 class="font-classic text-3xl text-navy-900">Novo Usuário</h1>
            </div>
            <a href="{{ route('users.index') }}"
                class="text-xs font-bold text-slate-500 hover:text-navy-900 transition-colors uppercase tracking-wider">←
                Voltar</a>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
            @include('partials.messages.errors')

            <form action="{{ route('users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                <div>
                    <label class="block text-xs uppercase font-bold text-navy-900 mb-2">Nome Completo</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nome do usuário"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:border-gold-500 focus:outline-none text-sm transition-all">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-navy-900 mb-2">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="email@exemplo.com"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 font-mono focus:border-gold-500 focus:outline-none text-sm transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs uppercase font-bold text-navy-900 mb-2">Função / Perfil</label>
                    <select name="role" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:border-gold-500 focus:outline-none text-sm transition-all">
                        <option value="" disabled selected>Selecione uma função...</option>
                        <option value="admin">Administrador</option>
                        <option value="teacher">Professor</option>
                        <option value="preceptor">Preceptor</option>
                    </select>
                </div>

                <div x-data="{ showPassword: false }">
                    <label class="block text-xs uppercase font-bold text-navy-900 mb-2">Senha</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-3 text-slate-800 focus:border-gold-500 focus:outline-none text-sm transition-all">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-navy-900 transition-colors">
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

                <div x-data="{ showPassword: false }">
                    <label class="block text-xs uppercase font-bold text-navy-900 mb-2">Confirmar Senha</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" required
                            placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-3 text-slate-800 focus:border-gold-500 focus:outline-none text-sm transition-all">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-navy-900 transition-colors">
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

                <div class="md:col-span-2 flex justify-end pt-4">
                    <button type="submit"
                        class="px-6 py-3 bg-navy-900 text-white font-bold rounded-xl hover:bg-gold-600 transition-all text-xs uppercase tracking-wider shadow-lg shadow-navy-900/20">
                        Cadastrar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
