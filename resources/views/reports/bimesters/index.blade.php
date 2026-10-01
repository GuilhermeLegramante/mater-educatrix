@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-fade-in">

        {{-- CABEÇALHO DA PÁGINA --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 pb-6">
            <div>
                <p class="text-gold-600 font-bold uppercase tracking-widest text-[10px] mb-1">
                    Relatório Institucional
                </p>
                <h1 class="font-classic text-3xl md:text-4xl text-navy-900 uppercase tracking-tight">
                    Gestão de Conceitos por Turma
                </h1>
                <p class="text-slate-500 text-xs font-medium mt-1">
                    Visualização consolidada de conceitos bimestrais de todos os estudantes.
                </p>
            </div>
        </div>

        {{-- PAINEL DE FILTROS --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <form method="GET" action="{{ route('reports.bimesters.index') }}"
                class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

                {{-- Filtro por Turma --}}
                <div class="space-y-2">
                    <label for="classroom_id" class="block font-bold text-navy-900 text-[10px] uppercase tracking-widest">
                        Turma <span class="text-rose-500">*</span>
                    </label>
                    <select name="classroom_id" id="classroom_id"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-navy-900 font-semibold focus:border-gold-500 focus:ring-1 focus:ring-gold-500 transition-all outline-none"
                        onchange="this.form.submit()">
                        <option value="">Selecione uma Turma</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}"
                                {{ $selectedClassroomId == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por Disciplina --}}
                <div class="space-y-2">
                    <label for="subject_id" class="block font-bold text-navy-900 text-[10px] uppercase tracking-widest">
                        Disciplina (Opcional)
                    </label>
                    <select name="subject_id" id="subject_id"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-navy-900 font-semibold focus:border-gold-500 focus:ring-1 focus:ring-gold-500 transition-all outline-none"
                        onchange="this.form.submit()">
                        <option value="">Todas as Disciplinas</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botão de Ação --}}
                <div>
                    <button type="submit"
                        class="w-full py-3 bg-gold-500 text-navy-900 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20 cursor-pointer">
                        Filtrar Resultados
                    </button>
                </div>
            </form>
        </div>

        {{-- TABELA MATRIZ DE CONCEITOS --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden backdrop-blur-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-slate-50 text-slate-400 text-[9px] uppercase font-black tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Aluno</th>
                            <th class="px-6 py-4">Disciplina</th>
                            <th class="px-4 py-4 text-center">1º Bimestre</th>
                            <th class="px-4 py-4 text-center">2º Bimestre</th>
                            <th class="px-4 py-4 text-center">3º Bimestre</th>
                            <th class="px-4 py-4 text-center">4º Bimestre</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($studentsData as $item)
                            @foreach ($item['subjects'] as $index => $subjectData)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    {{-- Nome do Aluno --}}
                                    @if ($index === 0)
                                        <td class="px-6 py-4 font-bold text-navy-900 text-sm align-top border-r border-slate-100 bg-slate-50/20"
                                            rowspan="{{ count($item['subjects']) }}">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-navy-900 text-gold-500 flex items-center justify-center font-classic text-sm shrink-0">
                                                    {{ mb_substr($item['student']->name, 0, 1) }}
                                                </div>
                                                <span class="uppercase tracking-tight">{{ $item['student']->name }}</span>
                                            </div>
                                        </td>
                                    @endif

                                    {{-- Disciplina --}}
                                    <td class="px-6 py-4 font-bold text-slate-600 text-xs">
                                        {{ $subjectData['subject']->name }}
                                    </td>

                                    {{-- Conceitos dos 4 Bimestres --}}
                                    @foreach ([1, 2, 3, 4] as $bimester)
                                        @php
                                            $concept = $subjectData['bimesters'][$bimester] ?? '-';
                                        @endphp
                                        <td class="px-4 py-4 text-center">
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl font-black text-[11px] shadow-sm transition-transform hover:scale-105
                                                {{ $concept != '-' ? 'bg-navy-900 text-gold-500 border border-navy-900' : 'bg-slate-100 text-slate-400' }}">
                                                {{ $concept }}
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 italic font-serif">
                                    Nenhum registro de conceito encontrado para os filtros selecionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
