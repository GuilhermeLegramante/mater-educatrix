@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-classic text-gold-500 uppercase tracking-tight">Novo Usuário</h2>
            <a href="{{ route('users.index') }}" class="text-xs text-slate-400 hover:text-white transition-colors">←
                Voltar</a>
        </div>

        <div class="bg-navy-900 rounded-3xl p-8 shadow-2xl border border-white/5">
            <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs uppercase font-bold text-gold-500 mb-2">Nome Completo</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold-500 focus:outline-none text-sm">
                    @error('name')
                        <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-gold-500 mb-2">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold-500 focus:outline-none text-sm">
                    @error('email')
                        <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-gold-500 mb-2">Função / Perfil</label>
                    <select name="role" required
                        class="w-full bg-navy-900 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold-500 focus:outline-none text-sm">
                        <option value="admin">Administrador</option>
                        <option value="teacher">Professor</option>
                        <option value="preceptor">Preceptor</option>
                    </select>
                    @error('role')
                        <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs uppercase font-bold text-gold-500 mb-2">Senha</label>
                        <input type="password" name="password" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold-500 focus:outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-bold text-gold-500 mb-2">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold-500 focus:outline-none text-sm">
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-gold-500 text-navy-950 font-bold rounded-xl hover:bg-gold-400 transition-all text-xs uppercase tracking-wider">
                        Cadastrar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
