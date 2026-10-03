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
                    Visualização consolidada dos conceitos bimestrais (A ao F) por estudante.
                </p>
            </div>
        </div>

        {{-- PAINEL DE FILTROS --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <form id="filter-form" method="GET" action="{{ route('reports.bimesters.index') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">

                {{-- Filtro por Turma --}}
                <div class="space-y-2">
                    <label for="classroom_id" class="block font-bold text-navy-900 text-[10px] uppercase tracking-widest">
                        Turma <span class="text-rose-500">*</span>
                    </label>
                    <select name="classroom_id" id="classroom_id"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-navy-900 font-semibold focus:border-gold-500 focus:ring-1 focus:ring-gold-500 transition-all outline-none"
                        onchange="resetStudentAndSubmit(this.form)">
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

                {{-- Filtro por Aluno --}}
                <div class="space-y-2">
                    <label for="student_id" class="block font-bold text-navy-900 text-[10px] uppercase tracking-widest">
                        Aluno (Opcional)
                    </label>
                    <select name="student_id" id="student_id"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-navy-900 font-semibold focus:border-gold-500 focus:ring-1 focus:ring-gold-500 transition-all outline-none"
                        onchange="this.form.submit()">
                        <option value="">Todos os Alunos</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ $selectedStudentId == $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botão de Submissão --}}
                <div>
                    <button type="submit"
                        class="w-full py-3 bg-gold-500 text-navy-900 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20 cursor-pointer">
                        Filtrar Resultados
                    </button>
                </div>
            </form>
        </div>

        {{-- LEGENDA DE CORES DOS CONCEITOS --}}
        <div
            class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-wrap items-center justify-between gap-4 text-xs font-semibold text-slate-600">
            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Legenda de Conceitos:</span>
            <div class="flex flex-wrap items-center gap-3">
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-bold"><span
                        class="w-2 h-2 rounded-full bg-emerald-500"></span> A - Excelente</span>
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-100 text-blue-800 font-bold"><span
                        class="w-2 h-2 rounded-full bg-blue-500"></span> B - Bom</span>
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 font-bold"><span
                        class="w-2 h-2 rounded-full bg-amber-500"></span> C - Regular</span>
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-orange-100 text-orange-800 font-bold"><span
                        class="w-2 h-2 rounded-full bg-orange-500"></span> D - Insuficiente</span>
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-100 text-rose-800 font-bold"><span
                        class="w-2 h-2 rounded-full bg-rose-500"></span> E / F - Crítico</span>
            </div>
        </div>

        {{-- TABELA DE CONCEITOS --}}
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
                            @php
                                $student = $item['student'];
                            @endphp

                            @foreach ($item['subjects'] as $index => $subjectData)
                                @php
                                    $subject = $subjectData['subject'];
                                @endphp

                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    {{-- Nome do Aluno --}}
                                    @if ($index === 0)
                                        <td class="px-6 py-4 font-bold text-navy-900 text-sm align-top border-r border-slate-100 bg-slate-50/20"
                                            rowspan="{{ count($item['subjects']) }}">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-navy-900 text-gold-500 flex items-center justify-center font-classic text-sm shrink-0">
                                                    {{ mb_substr($student->name, 0, 1) }}
                                                </div>
                                                <span class="uppercase tracking-tight">{{ $student->name }}</span>
                                            </div>
                                        </td>
                                    @endif

                                    {{-- Disciplina --}}
                                    <td class="px-6 py-4 font-bold text-slate-600 text-xs">
                                        {{ $subject->name }}
                                    </td>

                                    {{-- Conceitos e Notas dos 4 Bimestres --}}
                                    @foreach ([1, 2, 3, 4] as $bimester)
                                        @php
                                            $classroomId = $selectedClassroomId ?? request('classroom_id');

                                            // 1. Busca a nota formatada (0 a 10)
                                            $score = method_exists($student, 'getFormattedBimesterScore')
                                                ? $student->getFormattedBimesterScore(
                                                    $classroomId,
                                                    $subject->id,
                                                    $bimester,
                                                )
                                                : '-';

                                            // 2. Busca o conceito prévio (automático)
                                            $automaticConcept = method_exists($student, 'getConcept')
                                                ? $student->getConcept($classroomId, $subject->id, $bimester)
                                                : null;

                                            // 3. Obtém o conceito final consolidado retornado pelo Controller
                                            $finalConcept = $subjectData['bimesters'][$bimester] ?? '-';

                                            // 4. Verifica se houve alteração manual
                                            $isOverridden =
                                                !empty($automaticConcept) &&
                                                !empty($finalConcept) &&
                                                $finalConcept !== '-' &&
                                                $finalConcept !== $automaticConcept;

                                            $rawConcept = strtoupper(trim($finalConcept));

                                            $badgeClasses = match ($rawConcept) {
                                                'A' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                                'B' => 'bg-blue-100 text-blue-800 border-blue-300',
                                                'C' => 'bg-amber-100 text-amber-800 border-amber-300',
                                                'D' => 'bg-orange-100 text-orange-800 border-orange-300',
                                                'E', 'F' => 'bg-rose-100 text-rose-800 border-rose-300',
                                                default => 'bg-slate-100 text-slate-400 border-slate-200',
                                            };
                                        @endphp

                                        <td class="px-4 py-4 text-center align-middle">
                                            <div class="flex flex-col items-center gap-1">
                                                {{-- Área dos Conceitos --}}
                                                <div class="flex items-center justify-center gap-1">
                                                    @if ($isOverridden)
                                                        <span class="text-[10px] font-mono text-slate-400 line-through"
                                                            title="Conceito Prévio">
                                                            {{ $automaticConcept }}
                                                        </span>
                                                        <span class="text-[10px] text-slate-300">→</span>
                                                    @endif

                                                    <span
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-xl font-black text-xs border shadow-sm transition-transform hover:scale-110 {{ $badgeClasses }}"
                                                        title="{{ $isOverridden ? 'Conceito ajustado pelo professor' : 'Conceito Bimestral' }}">
                                                        {{ $rawConcept }}
                                                    </span>
                                                </div>

                                                {{-- Nota e Tag "Alt." --}}
                                                <div class="flex items-center gap-1">
                                                    <span
                                                        class="text-[9px] font-mono font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded"
                                                        title="Nota do aluno">
                                                        {{ $score ?: '-' }}
                                                    </span>

                                                    @if ($isOverridden)
                                                        <span
                                                            class="px-1 py-0.2 rounded text-[7px] font-black uppercase bg-amber-100 text-amber-800 border border-amber-200"
                                                            title="Conceito alterado manualmente">
                                                            Alt.
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
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

    {{-- SCRIPT AUXILIAR DE LIMPEZA --}}
    <script>
        function resetStudentAndSubmit(form) {
            const studentSelect = form.querySelector('#student_id');
            if (studentSelect) {
                studentSelect.value = ''; // Reseta o seletor de aluno para o valor padrão
            }
            form.submit();
        }
    </script>
@endsection
