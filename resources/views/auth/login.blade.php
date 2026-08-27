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
                                <label for="password" class="text-sm text-slate-300">
                                    Senha
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-sm text-slate-500 hover:text-slate-300">
                                        Esqueceu?
                                    </a>
                                @endif
                            </div>

                            {{-- Contêiner do Input com Alpine.js --}}
                            <div x-data="{ showPassword: false }" class="relative w-full">
                                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                                    placeholder="Digite sua senha"
                                    class="w-full h-12 pl-4 pr-12 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder:text-slate-500 focus:outline-none focus:border-amber-500 transition">

                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-amber-500 focus:outline-none transition">
                                    <!-- Ícone Olho Aberto -->
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" x-cloak>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>

                                    <!-- Ícone Olho Fechado -->
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 014.122-.963c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21f-9-9m0 0L3 3" />
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
