@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-classic text-gold-500 uppercase tracking-tight">Editar Usuário</h2>
            <a href="{{ route('users.index') }}" class="text-xs text-slate-400 hover:text-white transition-colors">←
                Voltar</a>
        </div>

        <div class="bg-navy-900 rounded-3xl p-8 shadow-2xl border border-white/5">
            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs uppercase font-bold text-gold-500 mb-2">Nome Completo</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold-500 focus:outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-gold-500 mb-2">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold-500 focus:outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-gold-500 mb-2">Função / Perfil</label>
                    <select name="role" required
                        class="w-full bg-navy-900 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-gold-500 focus:outline-none text-sm">
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Professor</option>
                        <option value="preceptor" {{ $user->role == 'preceptor' ? 'selected' : '' }}>Preceptor</option>
                    </select>
                </div>

                <div class="p-4 bg-white/5 rounded-2xl border border-white/5 space-y-4">
                    <p class="text-xs text-slate-400 font-bold uppercase">Alterar Senha (deixe em branco para manter a
                        atual)</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-gold-500 mb-1">Nova Senha</label>
                            <input type="password" name="password"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-gold-500 focus:outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-gold-500 mb-1">Confirmar Nova
                                Senha</label>
                            <input type="password" name="password_confirmation"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-gold-500 focus:outline-none text-sm">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-gold-500 text-navy-950 font-bold rounded-xl hover:bg-gold-400 transition-all text-xs uppercase tracking-wider">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
