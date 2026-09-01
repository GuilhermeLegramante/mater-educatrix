@extends('layouts.app')

@section('content')
    <div
        class="max-w-6xl mx-auto p-8 bg-white rounded-3xl shadow-sm border border-slate-100 backdrop-blur-sm transition-all">

        {{-- Cabeçalho da Página --}}
        <div class="flex justify-between items-end border-b border-slate-200 pb-6 mb-6">
            <div>
                <span class="text-gold-600 font-bold tracking-widest text-xs uppercase">Acessos</span>
                <h1 class="font-classic text-4xl text-navy-900 transition-colors">Listagem de Usuários</h1>
            </div>

            <button onclick="document.getElementById('form-usuario').classList.toggle('hidden')"
                class="bg-navy-900 text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-navy-900/20">
                + Adicionar Usuário
            </button>
        </div>

        @php
            $isEdit = isset($user);
        @endphp

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
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                        placeholder="Ex: João Silva" required
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
                        <option value="secretary"
                            {{ old('role', $user->role->value ?? ($user->role ?? '')) == 'secretary' ? 'selected' : '' }}>
                            Secretária</option>
                    </select>
                </div>

                {{-- Seleção Múltipla de Turmas --}}
                <div class="md:col-span-2 bg-white/5 border border-white/10 p-4 rounded-xl">
                    <label class="block text-xs uppercase font-bold text-gold-500 mb-2">Vincular Turmas</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-32 overflow-y-auto">
                        @foreach ($classrooms as $classroom)
                            <label class="flex items-center space-x-2 text-xs text-slate-300 cursor-pointer">
                                <input type="checkbox" name="classrooms[]" value="{{ $classroom->id }}"
                                    {{ isset($user) && $user->classrooms->contains($classroom->id) ? 'checked' : '' }}
                                    class="rounded bg-white/10 border-white/20 text-gold-500 focus:ring-0">
                                <span>{{ $classroom->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Seleção Múltipla de Disciplinas --}}
                <div class="md:col-span-2 bg-white/5 border border-white/10 p-4 rounded-xl">
                    <label class="block text-xs uppercase font-bold text-gold-500 mb-2">Vincular Disciplinas</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-32 overflow-y-auto">
                        @foreach ($subjects as $subject)
                            <label class="flex items-center space-x-2 text-xs text-slate-300 cursor-pointer">
                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                    {{ isset($user) && $user->subjects->contains($subject->id) ? 'checked' : '' }}
                                    class="rounded bg-white/10 border-white/20 text-gold-500 focus:ring-0">
                                <span>{{ $subject->name }}</span>
                            </label>
                        @endforeach
                    </div>
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
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" style="display: none;">
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

        {{-- Tabela de Dados com Suporte ao DataTables --}}
        <div class="text-slate-600 text-sm overflow-x-auto custom-dark-datatable">
            <table id="users-table" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] uppercase font-black text-slate-400 bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-4">Nome</th>
                        <th class="px-8 py-4">E-mail</th>
                        <th class="px-8 py-4">Função</th>
                        <th class="px-8 py-4 text-right">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50/40 transition-colors group">
                            <td class="px-8 py-5 font-bold text-navy-900 group-hover:text-gold-500 transition-colors">
                                {{ $user->name }}
                            </td>
                            <td class="px-8 py-5 text-slate-500 font-mono text-sm">
                                {{ $user->email }}
                            </td>
                            <td class="px-8 py-5 text-slate-500 text-sm font-medium">
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-navy-900">
                                    {{ $user->role_label }}
                                </span>
                            </td>
                            <td class="px-8 py-5 flex justify-end gap-1.5">
                                <a href="{{ route('users.edit', $user) }}"
                                    class="p-2 hover:bg-slate-100 rounded-xl text-slate-600 transition-colors"
                                    title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('users.destroy', $user) }}" method="POST"
                                    class="form-delete inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 hover:bg-red-50 rounded-xl text-red-400 transition-colors"
                                        title="Excluir">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#users-table').DataTable({
                responsive: true,
                pageLength: 10,
                ordering: true,
                searching: true,
                lengthChange: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                }
            });
        });
    </script>
@endpush
