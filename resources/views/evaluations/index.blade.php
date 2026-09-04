@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="mb-6 p-4 bg-gold-500/10 border border-gold-500/20 rounded-2xl text-gold-600 text-sm font-bold flex items-center justify-between animate-fade-in">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-gold-600/50 hover:text-gold-600">&times;</button>
        </div>
    @endif

    <div class="max-w-6xl mx-auto animate-fade-in" x-data="{ deleteOpen: false, deleteUrl: '' }">
        <div class="flex justify-between items-end mb-8 border-b border-slate-200 pb-6">
            <div>
                <h1 class="font-classic text-4xl text-navy-900 transition-colors">Gestão de Avaliações</h1>
                <p class="text-slate-500">Visualize e gerencie as atividades acadêmicas aplicadas.</p>
            </div>
            <a href="{{ route('evaluations.create') }}"
                class="bg-gold-500 text-navy-950 px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Nova Avaliação
            </a>
        </div>

        {{-- BARRA DE FILTROS --}}
        <form method="GET" action="{{ route('evaluations.index') }}"
            class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

            {{-- Busca por Texto --}}
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Buscar por Título</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ex: Prova Mensal..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
            </div>

            {{-- Filtro por Disciplina --}}
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Disciplina</label>
                <select name="subject_id" onchange="this.form.submit()"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
                    <option value="">Todas as Disciplinas</option>
                    @foreach ($subjects as $s)
                        <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro por Bimestre --}}
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Bimestre</label>
                <select name="bimester" onchange="this.form.submit()"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
                    <option value="">Todos os Bimestres</option>
                    <option value="1" {{ request('bimester') == '1' ? 'selected' : '' }}>1º Bimestre</option>
                    <option value="2" {{ request('bimester') == '2' ? 'selected' : '' }}>2º Bimestre</option>
                    <option value="3" {{ request('bimester') == '3' ? 'selected' : '' }}>3º Bimestre</option>
                    <option value="4" {{ request('bimester') == '4' ? 'selected' : '' }}>4º Bimestre</option>
                </select>
            </div>

            {{-- Botão de Limpar Filtros --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="w-full bg-navy-900 text-white font-black py-2.5 rounded-xl text-xs uppercase tracking-wider hover:bg-gold-600 hover:text-navy-950 transition-all">
                    Filtrar
                </button>
                @if (request()->hasAny(['search', 'subject_id', 'bimester']))
                    <a href="{{ route('evaluations.index') }}"
                        class="px-4 py-2.5 bg-slate-100 text-slate-500 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors"
                        title="Limpar Filtros">
                        ✕
                    </a>
                @endif
            </div>
        </form>

        {{-- TABELA DE AVALIAÇÕES --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] uppercase font-black text-slate-400 bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4">Avaliação</th>
                        <th class="px-6 py-4 hidden md:table-cell">Disciplina</th>
                        <th class="px-6 py-4 text-center">Bimestre</th>
                        <th class="px-6 py-4 text-center">Scores</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($evaluations as $evaluation)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-navy-900">{{ $evaluation->title }}</div>
                                <div class="text-[10px] text-slate-400 md:hidden">{{ $evaluation->subject->name }}</div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <span class="text-xs font-semibold text-slate-600 uppercase tracking-tight">
                                    {{ $evaluation->subject->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-bold text-slate-500">
                                    {{ $evaluation->bimester }}º Bim.
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-block px-2 py-1 rounded-lg bg-slate-100 text-navy-900 font-mono font-bold text-xs">
                                    {{ $evaluation->max_score }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('evaluations.show', $evaluation->id) }}"
                                        class="p-2 text-slate-400 hover:text-navy-900 transition-colors"
                                        title="Ver Detalhes">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('evaluations.edit', $evaluation->id) }}"
                                        class="p-2 text-slate-400 hover:text-gold-600 transition-colors"
                                        title="Editar Avaliação">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('grades.create', ['classroom' => $evaluation->classroom_id, 'evaluation' => $evaluation->id]) }}"
                                        class="bg-navy-900 text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-tighter hover:scale-105 transition-transform shadow-md">
                                        Notas
                                    </a>

                                    <button type="button"
                                        @click="deleteUrl = '{{ route('evaluations.destroy', $evaluation->id) }}'; deleteOpen = true"
                                        class="p-2 text-slate-400 hover:text-rose-600 transition-colors"
                                        title="Excluir Avaliação">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400 text-sm font-semibold">
                                Nenhuma avaliação encontrada com os filtros selecionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINAÇÃO DO LARAVEL --}}
        <div class="mt-6">
            {{ $evaluations->links() }}
        </div>

        {{-- MODAL DE CONFIRMAÇÃO DE EXCLUSÃO --}}
        <div x-show="deleteOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/70 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="deleteOpen = false"
                class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 text-center relative overflow-hidden">
                <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-rose-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="font-classic text-2xl text-navy-900 mb-2">Excluir Avaliação?</h3>
                <p class="text-xs text-slate-500 mb-8 leading-relaxed">
                    Esta ação é irreversível. Todas as notas e scores associados a esta avaliação também serão
                    permanentemente removidos.
                </p>

                <div class="flex gap-3">
                    <button type="button" @click="deleteOpen = false"
                        class="w-1/2 bg-slate-100 text-slate-600 font-black py-3.5 rounded-xl text-xs uppercase tracking-wider hover:bg-slate-200 transition-colors">
                        Cancelar
                    </button>

                    <form :action="deleteUrl" method="POST" class="w-1/2">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full bg-rose-600 text-white font-black py-3.5 rounded-xl text-xs uppercase tracking-wider hover:bg-rose-700 transition-colors shadow-lg shadow-rose-600/20">
                            Sim, Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
