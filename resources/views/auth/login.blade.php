@extends('layouts.auth')

@section('content')
<div class="min-h-screen bg-slate-950">

    <div class="min-h-screen flex items-center justify-center px-6">

        <div class="w-full max-w-md">

            {{-- Logo --}}
            <div class="text-center mb-10">

                <img
                    src="/img/logo.png"
                    alt="Mater Educatrix"
                    class="h-20 mx-auto mb-6">

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

                        <label
                            for="email"
                            class="block text-sm text-slate-300 mb-2">

                            E-mail

                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="Digite seu e-mail"
                            class="
                            w-full
                            h-12
                            px-4
                            rounded-xl
                            bg-slate-950
                            border
                            border-slate-800
                            text-white
                            placeholder:text-slate-500
                            focus:outline-none
                            focus:border-amber-500
                            transition">

                    </div>

                    {{-- Senha --}}
                    <div>

                        <div class="flex justify-between mb-2">

                            <label
                                for="password"
                                class="text-sm text-slate-300">

                                Senha

                            </label>

                            @if (Route::has('password.request'))
                                <a
                                    href="{{ route('password.request') }}"
                                    class="text-sm text-slate-500 hover:text-slate-300">

                                    Esqueceu?

                                </a>
                            @endif

                        </div>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="
                            w-full
                            h-12
                            px-4
                            rounded-xl
                            bg-slate-950
                            border
                            border-slate-800
                            text-white
                            focus:outline-none
                            focus:border-amber-500
                            transition">

                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center">

                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="rounded border-slate-700 bg-slate-900">

                        <label
                            for="remember_me"
                            class="ml-3 text-sm text-slate-400">

                            Manter conectado

                        </label>

                    </div>

                    {{-- Botão --}}
                    <button
                        type="submit"
                        class="
                        w-full
                        h-12
                        rounded-xl
                        bg-white
                        text-slate-900
                        font-semibold
                        hover:bg-slate-100
                        transition">

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
