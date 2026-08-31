@extends('layouts.app')

@section('content')
    <div x-data="{ showDeleteModal: false, deleteUrl: '' }" class="space-y-6">

        {{-- Cabeçalho da Página --}}
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-classic text-gold-500 uppercase tracking-tight">Gestão de Usuários</h2>
                <p class="text-slate-400 text-xs">Administre as contas de acesso ao sistema</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="flex items-center gap-2 px-4 py-2 bg-gold-500 text-navy-950 font-bold rounded-xl hover:bg-gold-400 transition-all text-xs uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Usuário
            </a>
        </div>

        {{-- Tabela de Usuários --}}
        <div class="bg-navy-900 rounded-3xl p-6 shadow-2xl border border-white/5 relative overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-white/5 text-gold-500 uppercase text-[10px] tracking-widest border-b border-white/10">
                        <tr>
                            <th class="p-4">Nome</th>
                            <th class="p-4">E-mail</th>
                            <th class="p-4">Função</th>
                            <th class="p-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($users as $user)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="p-4 font-bold text-white">{{ $user->name }}</td>
                                <td class="p-4 font-mono text-xs text-slate-400">{{ $user->email }}</td>
                                <td class="p-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/5 border border-white/10 text-gold-400">
                                        {{ $user->role ?? 'Acesso Geral' }}
                                    </span>
                                </td>
                                <td class="p-4 text-right space-x-2">
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="inline-block p-2 text-slate-400 hover:text-gold-500 transition-colors"
                                        title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <button type="button"
                                        @click="deleteUrl = '{{ route('users.destroy', $user) }}'; showDeleteModal = true"
                                        class="p-2 text-slate-400 hover:text-rose-400 transition-colors cursor-pointer"
                                        title="Excluir">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-500 italic">Nenhum usuário cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>

        {{-- Modal de Exclusão Segura --}}
        <div x-show="showDeleteModal" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="showDeleteModal = false"
                class="bg-navy-900 border border-white/10 rounded-3xl p-6 max-w-md w-full shadow-2xl relative text-center">
                <h4 class="text-lg font-bold text-white mb-2">Confirmar Exclusão</h4>
                <p class="text-slate-400 text-xs mb-6">Tem certeza que deseja apagar este usuário?</p>

                <form :action="deleteUrl" method="POST" class="flex justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="showDeleteModal = false"
                        class="px-5 py-2.5 bg-white/5 text-slate-300 rounded-xl text-xs font-bold uppercase">Cancelar</button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold uppercase shadow-lg shadow-rose-600/30">Excluir</button>
                </form>
            </div>
        </div>

    </div>
@endsection
