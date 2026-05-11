@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <nav class="flex mb-2 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                    <a href="{{ route('evaluations.index') }}" class="hover:text-navy-900 transition-colors">Avaliações</a>
                    <span class="mx-2">/</span>
                    <span class="text-gold-600">Detalhes do Score</span>
                </nav>
                <h2 class="font-classic text-3xl text-navy-900 dark:text-white">{{ $evaluation->title }}</h2>
                <p class="text-slate-500 font-bold text-xs uppercase tracking-tighter">
                    {{ $evaluation->subject->name }} • {{ $evaluation->classroom->name }} • {{ $evaluation->bimester }}º
                    Bimestre
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('grades.create', [$evaluation->classroom_id, $evaluation->id]) }}"
                    class="bg-gold-500 text-navy-950 px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar Scores
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-navy-900 p-6 rounded-3xl text-white shadow-xl">
                <span class="text-[10px] font-black uppercase tracking-widest text-gold-500/50">Média da Turma</span>
                <div class="text-3xl font-classic mt-1">
                    {{ number_format($evaluation->grades->avg('score') ?? 0, 1) }}
                    <span class="text-sm text-white/30">/ {{ $evaluation->max_score }}</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total de Lançamentos</span>
                <div class="text-3xl font-classic mt-1 text-navy-900">
                    {{ $evaluation->grades->count() }} <span class="text-sm text-slate-300">alunos</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Peso da Atividade</span>
                <div class="text-3xl font-classic mt-1 text-navy-900">
                    {{ number_format($evaluation->weight, 1) }} <span class="text-sm text-slate-300">pontos</span>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-navy-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50">
                <h3 class="font-classic text-lg text-navy-900 dark:text-gold-500">Resultados Consolidados</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-navy-950/50">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Estudante
                            </th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">
                                Score</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">
                                Aproveitamento</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                Conceito</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($evaluation->classroom->students->sortBy('name') as $student)
                            @php
                                // Busca a nota deste aluno especificamente para esta avaliação
                                $grade = $evaluation->grades->where('student_id', $student->id)->first();
                                $percentage = $grade ? ($grade->score / $evaluation->max_score) * 100 : 0;

                                // Usando o método de cálculo de conceito que você tem no model Student
                                $concept = $grade ? $student->calculateGradeConcept($percentage) : '--';
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-navy-900 dark:text-slate-200">{{ $student->name }}</div>
                                    <div class="text-[9px] text-slate-400 uppercase tracking-tighter">Matrícula:
                                        #{{ $student->id }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($grade)
                                        <span class="font-mono font-black text-navy-900 dark:text-gold-500 text-lg">
                                            {{ number_format($grade->score, 1) }}
                                        </span>
                                        <span class="text-slate-300 text-xs">/ {{ $evaluation->max_score }}</span>
                                    @else
                                        <span
                                            class="text-slate-300 italic text-xs font-bold uppercase tracking-widest">Pendente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <div
                                            class="w-24 bg-slate-100 dark:bg-navy-950 h-2 rounded-full overflow-hidden shadow-inner">
                                            <div class="h-full transition-all duration-1000 {{ $percentage >= 60 ? 'bg-emerald-500' : 'bg-gold-500' }}"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-500 w-8">
                                            {{ number_format($percentage, 0) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-xl font-black text-sm transition-all
                                    {{ $grade ? 'bg-navy-900 text-gold-500 shadow-sm' : 'bg-slate-100 text-slate-300' }}">
                                        {{ $concept }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 flex justify-center">
            <a href="{{ route('evaluations.index') }}"
                class="text-slate-400 hover:text-navy-900 font-bold text-xs uppercase tracking-widest transition-colors">
                ← Voltar para listagem
            </a>
        </div>
    </div>
@endsection
