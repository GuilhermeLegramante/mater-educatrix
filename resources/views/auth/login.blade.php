@extends('layouts.auth')

@section('content')
    <div class="min-h-screen bg-slate-950">

        <div class="min-h-screen flex items-center justify-center px-6">

            <div class="w-full max-w-md">

                {{-- Logo --}}
                <div class="text-center mb-10">

                    <img src="/img/logo.png" alt="Mater Educatrix" class="h-20 mx-auto mb-6">

                    <h1 class="text-5xl font-serif text-white tracking-tight">
                        Mater Educatrix
                    </h1>

                    <p class="mt-3 text-sm text-slate-400">
                        Portal Acadêmico
                    </p>

                </div>

                {{-- Card moderno --}}
                <div
                    class="
                bg-slate-900/70
                backdrop-blur-xl
                border
                border-slate-800
                rounded-3xl
                p-8
                shadow-2xl">

                    @if ($errors->any())
                        <div class="mb-6 rounded-xl bg-red-500/10 border border-red-500/20 p-4">
                            @foreach ($errors->all() as $error)
                                <div class="text-red-300 text-sm">
                                    {{ $error }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm text-slate-300 mb-2">
                                E-mail
                            </label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus placeholder="Digite seu e-mail"
                                class="w-full h-12 px-4 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder:text-slate-500 focus:outline-none focus:border-amber-500 transition">
                        </div>

                        {{-- Senha --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="password" class="text-sm font-medium text-slate-300">
                                    Senha
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-xs font-semibold text-amber-500/80 hover:text-amber-400 transition-colors">
                                        Esqueceu?
                                    </a>
                                @endif
                            </div>

                            {{-- Campo de Senha com Botão Embutido Corrigido --}}
                            <div x-data="{ showPassword: false }" class="relative flex items-center w-full">

                                <!-- Input de Senha -->
                                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                                    placeholder="••••••••"
                                    class="w-full h-12 pl-4 pr-12 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder:text-slate-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-200">

                                <!-- Botão de Alternar Visibilidade (Posicionado à Direita) -->
                                <button type="button" @click="showPassword = !showPassword" tabindex="-1"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 h-8 w-8 flex items-center justify-center rounded-lg text-slate-500 hover:text-slate-200 hover:bg-slate-800/60 focus:outline-none transition-all duration-200"
                                    title="Alternar visibilidade da senha">

                                    <!-- Ícone Olho Fechado (Senha Oculta) -->
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                        stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>

                                    <!-- Ícone Olho Aberto (Senha Visível) - SVG Corrigido -->
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                        stroke-width="1.75" viewBox="0 0 24 24" x-cloak>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12c1.86-3.834 5.662-6.5 10.024-6.5 4.362 0 8.164 2.666 10.024 6.5-1.86 3.834-5.662 6.5-10.024 6.5-4.362 0-8.164-2.666-10.024-6.5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Remember --}}
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-slate-700 bg-slate-900">
                            <label for="remember_me" class="ml-3 text-sm text-slate-400">
                                Manter conectado
                            </label>
                        </div>

                        {{-- Botão --}}
                        <button type="submit"
                            class="w-full h-12 rounded-xl bg-white text-slate-900 font-semibold hover:bg-slate-100 transition">
                            Entrar
                        </button>
                    </form>

                </div>

                {{-- Rodapé --}}
                <div class="mt-8 text-center">

                    <p class="text-xs text-slate-600">
                        © {{ date('Y') }} Mater Educatrix
                    </p>

                </div>

            </div>

        </div>

    </div>
@endsection
