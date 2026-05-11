@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="mb-6 p-4 bg-gold-500/10 border border-gold-500/20 rounded-2xl text-gold-600 dark:text-gold-400 text-sm font-bold flex items-center justify-between animate-fade-in">
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

    <div class="max-w-6xl mx-auto animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="font-classic text-2xl text-navy-900 dark:text-gold-500">Gestão de Avaliações</h2>
                <p class="text-slate-500 text-sm">Visualize e gerencie as atividades acadêmicas aplicadas.</p>
            </div>
            <a href="{{ route('evaluations.create') }}"
                class="bg-gold-500 text-navy-950 px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Nova Avaliação
            </a>
        </div>

        <div
            class="bg-white dark:bg-navy-900 shadow-xl rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-navy-950/50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Avaliação</th>
                        <th
                            class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 hidden md:table-cell">
                            Disciplina</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">
                            Scores</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                            Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($evaluations as $evaluation)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-navy-900 dark:text-white">{{ $evaluation->title }}</div>
                                <div class="text-[10px] text-slate-400 md:hidden">{{ $evaluation->subject->name }}</div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <span
                                    class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-tight">
                                    {{ $evaluation->subject->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-block px-2 py-1 rounded-lg bg-slate-100 dark:bg-navy-950 text-navy-900 dark:text-gold-500 font-mono font-bold text-xs">
                                    {{ $evaluation->max_score }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-3">
                                    <a href="{{ route('evaluations.show', $evaluation->id) }}"
                                        class="p-2 text-slate-400 hover:text-navy-900 dark:hover:text-gold-500 transition-colors"
                                        title="Ver Detalhes">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('grades.create', ['classroom' => $evaluation->classroom_id, 'evaluation' => $evaluation->id]) }}"
                                        class="bg-navy-900 dark:bg-gold-500 text-white dark:text-navy-950 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-tighter hover:scale-105 transition-transform shadow-md">
                                        Scores
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $evaluations->links() }}
        </div>
    </div>
@endsection
