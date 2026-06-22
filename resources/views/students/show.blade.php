@extends('layouts.app')

@section('content')
    @if (!$activeClassroom)
        {{-- TELA: ALUNO NÃO MATRICULADO --}}
        <div
            class="max-w-4xl mx-auto bg-white border border-slate-100 rounded-3xl p-10 text-center shadow-sm backdrop-blur-sm transition-all">

            <div class="text-6xl mb-4">📚</div>

            <h2 class="text-2xl font-classic text-navy-900 mb-2">
                Aluno ainda não matriculado
            </h2>

            <p class="text-slate-500 font-mono text-sm uppercase tracking-widest">
                Este aluno não possui matrícula active em nenhuma turma.
            </p>

            <div class="mt-6">
                <button onclick="openModal('modal-enroll')"
                    class="px-6 py-3 bg-gold-500 text-navy-900 rounded-xl font-black uppercase text-xs hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20">
                    Matricular Aluno
                </button>
            </div>
        </div>
    @else
        <div class="max-w-7xl mx-auto space-y-8 animate-fade-in">

            {{-- HEADER DO PERFIL --}}
            <div
                class="flex flex-col lg:flex-row justify-between gap-6 border-b border-slate-200 pb-6">

                <div class="flex items-center gap-6">
                    {{-- Avatar com iniciais --}}
                    <div
                        class="w-20 h-20 bg-navy-900 rounded-2xl flex items-center justify-center border-b-4 border-gold-500 shadow-xl shrink-0">
                        <span class="text-gold-500 font-classic text-4xl">
                            {{ mb_substr($student->name, 0, 1) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-gold-600 font-bold uppercase tracking-widest text-[10px] mb-1">
                            Desempenho Acadêmico
                        </p>

                        <h1
                            class="font-classic text-4xl lg:text-5xl text-navy-900 uppercase tracking-tight transition-colors">
                            {{ $student->name }}
                        </h1>

                        <div class="flex flex-wrap gap-2 mt-2">
                            <span
                                class="px-3 py-1 rounded-xl bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest">
                                Matrícula: {{ $student->registration_number }}
                            </span>

                            <span
                                class="px-3 py-1 rounded-xl bg-gold-500/10 text-gold-600 text-[10px] font-black uppercase tracking-widest border border-gold-500/20">
                                {{ $activeClassroom->name }} • {{ $activeClassroom->year }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- AÇÕES PRINCIPAIS --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- <a href="{{ route('students.report-card.pdf', [$activeClassroom, $student]) }}" target="_blank"
                        class="px-6 py-3 bg-navy-900 text-white border-2 border-transparent rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-navy-950 transition-all inline-block text-center decoration-none">
                        Gerar Boletim PDF
                    </a> --}}

                    <div x-data="{ openExportModal: false }">

                        <button @click="openExportModal = true"
                            class="px-6 py-3 bg-navy-900 text-white border-2 border-transparent rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-navy-950 transition-all text-center">
                            Gerar Boletim PDF
                        </button>

                        <div x-show="openExportModal" class="fixed inset-0 z-50 overflow-y-auto"
                            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            style="display: none;">

                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"
                                    @click="openExportModal = false">
                                </div>

                                <div
                                    class="inline-block w-full max-w-md p-7 my-8 overflow-hidden text-left align-middle transition-all transform bg-white border border-slate-200 shadow-2xl rounded-3xl relative z-10">

                                    <div
                                        class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                                        <div>
                                            <h4
                                                class="font-classic text-base font-bold text-navy-900 uppercase tracking-wider">
                                                Personalizar Documento
                                            </h4>
                                            <p class="text-[11px] text-slate-400 mt-0.5">Selecione os dados que farão parte
                                                do
                                                PDF impresso.</p>
                                        </div>
                                        <button @click="openExportModal = false"
                                            class="text-slate-400 hover:text-rose-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <form action="{{ route('students.report-card.pdf', [$activeClassroom, $student]) }}"
                                        method="GET" target="_blank" @submit="openExportModal = false">

                                        <div class="space-y-4 mb-6">
                                            <span
                                                class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-1">Componentes
                                                Adicionais</span>

                                            <div
                                                class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                                <div>
                                                    <span
                                                        class="block text-xs font-bold text-navy-900">Rendimento
                                                        Escolar</span>
                                                    <span class="text-[10px] text-slate-400">Notas, médias e faltas das
                                                        disciplinas básicas.</span>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="include_grades" value="1" checked
                                                        class="sr-only peer">
                                                    <div
                                                        class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                                                    </div>
                                                </label>
                                            </div>

                                            <div
                                                class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                                <div>
                                                    <span
                                                        class="block text-xs font-bold text-navy-900">Ocorrências
                                                        & Atendimentos</span>
                                                    <span class="text-[10px] text-slate-400">Histórico disciplinar e
                                                        prontuários
                                                        médicos.</span>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="include_occurrences" value="1"
                                                        class="sr-only peer">
                                                    <div
                                                        class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                                                    </div>
                                                </label>
                                            </div>

                                            <div
                                                class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                                <div>
                                                    <span
                                                        class="block text-xs font-bold text-navy-900">Relatos
                                                        de Preceptoria</span>
                                                    <span class="text-[10px] text-slate-400">Acompanhamento do tutor e
                                                        desenvolvimento pessoal.</span>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="include_preceptory" value="1"
                                                        class="sr-only peer">
                                                    <div
                                                        class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <div
                                            class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                            <button type="button" @click="openExportModal = false"
                                                class="px-5 py-2.5 text-xs font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-100 rounded-xl transition-colors">
                                                Cancelar
                                            </button>
                                            <button type="submit"
                                                class="bg-gold-500 text-navy-950 px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                                Compilar & Imprimir
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>



                    <button onclick="openConceptModal()"
                        class="px-6 py-3 bg-gold-500 text-navy-900 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20">
                        Lançar Conceito
                    </button>
                </div>
            </div>

            {{-- FILTRO DE BIMESTRES --}}
            <div class="flex flex-wrap gap-3">
                @for ($i = 1; $i <= 4; $i++)
                    <a href="{{ route('students.show', [$student, 'bimester' => $i]) }}"
                        class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all border
                        {{ $bimester == $i
                            ? 'bg-gold-500 text-navy-900 border-gold-500 shadow-lg shadow-gold-500/20'
                            : 'bg-white border-slate-200 text-slate-500 hover:border-gold-500 hover:text-gold-600' }}">
                        {{ $i }}º Bimestre
                    </a>
                @endfor
            </div>

            {{-- CORPO DASHBOARD: ESQUERDA (DISCIPLINAS) | DIREITA (CONTEÚDO) --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- SIDEBAR: SELEÇÃO DE DISCIPLINAS --}}
                <div class="space-y-4">
                    <h3 class="font-bold text-navy-900 uppercase text-[10px] tracking-widest px-2">
                        Conceito por Disciplina
                    </h3>

                    {{-- CARD: FILTRO TODAS --}}
                    <a href="{{ route('students.show', [$student, 'bimester' => $bimester]) }}" class="block">
                        <div
                            class="bg-white p-4 rounded-2xl border shadow-sm flex justify-between items-center transition-all
                            {{ !request('subject')
                                ? 'border-gold-500 ring-2 ring-gold-100'
                                : 'border-slate-100 hover:border-gold-500' }}">
                            <div>
                                <span
                                    class="block font-black text-navy-900 text-[11px] uppercase tracking-tight">
                                    Todas
                                </span>
                                <span class="text-[9px] text-slate-400 font-bold uppercase">
                                    Mostrar todas disciplinas
                                </span>
                            </div>
                            <div
                                class="w-10 h-10 bg-navy-900 text-gold-500 rounded-xl flex items-center justify-center font-black">
                                *
                            </div>
                        </div>
                    </a>

                    {{-- CARDS: DISCIPLINAS INDIVIDUAIS --}}
                    @foreach ($activeClassroom->subjects as $subject)
                        @php
                            $concept = $student->getConsolidatedConcept($activeClassroom->id, $subject->id, $bimester);
                            $activeSubject = request('subject') == $subject->id;
                        @endphp

                        <a href="{{ route('students.show', [$student, 'bimester' => $bimester, 'subject' => $subject->id]) }}"
                            class="block">
                            <div
                                class="bg-white p-4 rounded-2xl border shadow-sm flex justify-between items-center group transition-all
                                {{ $activeSubject
                                    ? 'border-gold-500 ring-2 ring-gold-100'
                                    : 'border-slate-100 hover:border-gold-500' }}">
                                <div>
                                    <span
                                        class="block font-black text-navy-900 text-[11px] uppercase tracking-tight group-hover:text-gold-500 transition-colors">
                                        {{ $subject->name }}
                                    </span>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase">
                                        Média Bimestral
                                    </span>
                                </div>

                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center font-black shadow-sm group-hover:scale-110 transition-transform
                                    {{ $concept == 'A'
                                        ? 'bg-navy-900 text-gold-500'
                                        : 'bg-slate-100 text-slate-600' }}">
                                    {{ $concept }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- COLUNA DIREITA: CONTEÚDO PRINCIPAL --}}
                <div class="lg:col-span-3 space-y-8">

                    {{-- BLOCCO: AVALIAÇÕES DO BIMESTRE --}}
                    <div
                        class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden backdrop-blur-sm">

                        {{-- Header do Card --}}
                        <div
                            class="px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                            <div>
                                <h3 class="font-classic text-xl text-navy-900">
                                    Avaliações do Bimestre
                                </h3>
                                <p
                                    class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">
                                    Desempenho quantitativo
                                </p>
                            </div>
                            <button onclick="toggleGradesCard()"
                                class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                                <svg id="grades-icon"
                                    class="w-5 h-5 text-slate-600 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>

                        {{-- Tabela de Notas --}}
                        <div id="grades-body" class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead
                                    class="bg-slate-50 text-slate-400 text-[9px] uppercase font-black">
                                    <tr>
                                        <th class="px-8 py-4">Avaliação / Disciplina</th>
                                        <th class="px-8 py-4 text-center">Score</th>
                                        <th class="px-8 py-4 text-center">Aproveitamento</th>
                                        <th class="px-8 py-4 text-center">Conceito</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse ($grades as $grade)
                                        @php
                                            $perc = ($grade->score / $grade->evaluation->max_score) * 100;
                                        @endphp
                                        <tr class="hover:bg-slate-50/40 transition-colors">
                                            <td class="px-8 py-4">
                                                <span class="font-bold text-navy-900 text-sm block">
                                                    {{ $grade->evaluation->title }}
                                                </span>
                                                <span
                                                    class="text-[10px] text-gold-600 font-bold uppercase tracking-widest">
                                                    {{ $grade->evaluation->subject->name }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-8 py-4 text-center font-mono text-xs text-slate-600">
                                                <span class="font-bold text-navy-900">
                                                    {{ $grade->score }}
                                                </span>
                                                <span class="text-slate-300 mx-1">de</span>
                                                {{ $grade->evaluation->max_score }}
                                            </td>
                                            <td class="px-8 py-4 text-center">
                                                <span
                                                    class="text-[11px] font-black {{ $perc >= 60 ? 'text-green-600' : 'text-red-500' }}">
                                                    {{ number_format($perc, 0) }}%
                                                </span>
                                            </td>
                                            <td class="px-8 py-4 text-center">
                                                <span
                                                    class="inline-block w-8 py-1 rounded bg-slate-100 text-slate-800 font-black text-[10px]">
                                                    {{ $student->getConsolidatedConcept(
                                                        $grade->evaluation->classroom_id,
                                                        $grade->evaluation->subject_id,
                                                        $grade->evaluation->bimester,
                                                    ) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="py-12 text-center text-slate-400 italic">
                                                Nenhuma avaliação encontrada.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-navy-900 rounded-3xl p-8 shadow-2xl relative overflow-hidden border border-transparent transition-all"
                        x-data="{ openOccurrenceModal: false }">

                        <div class="relative z-10">
                            <div class="flex justify-between items-center mb-8">
                                <div>
                                    <h3 class="font-classic text-2xl text-gold-500 uppercase tracking-tight">
                                        Ocorrências & Atendimentos
                                    </h3>
                                    <p
                                        class="text-white/40 text-[10px] font-bold uppercase tracking-widest">
                                        Histórico de intercorrências e prontuário do estudante
                                    </p>
                                </div>

                                <button @click="openOccurrenceModal = true"
                                    class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-gold-500 hover:bg-white/10 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest">
                                        Nova Ocorrência
                                    </span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @forelse($student->occurrences as $occurrence)
                                    <div
                                        class="bg-white/5 border-l-4 border-{{ $occurrence->type->color }}-500 p-5 sm:p-6 rounded-r-2xl hover:bg-white/[0.08] transition-colors relative group">

                                        <div class="flex justify-between items-start gap-4 mb-3">
                                            <div class="flex flex-col gap-0.5 min-w-0">
                                                <span
                                                    class="text-{{ $occurrence->type->color }}-400 font-bold text-[10px] uppercase tracking-widest truncate">
                                                    {{ $occurrence->type->name }}
                                                </span>
                                                <span class="text-white/30 text-[9px] truncate">
                                                    por {{ $occurrence?->user?->name }}
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <span
                                                    class="text-white/30 text-[10px] font-mono whitespace-nowrap pt-1">
                                                    {{ $occurrence->date->format('d/m/Y') }}
                                                    {{ $occurrence->time ? ' ' . substr($occurrence->time, 0, 5) : '' }}
                                                </span>

                                                <form action="{{ route('occurrences.destroy', $occurrence->id) }}"
                                                    method="POST" class="form-delete md:hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 -mr-2 text-slate-400 hover:text-rose-400 active:scale-95 transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <p
                                            class="text-white/80 font-sans text-sm leading-relaxed mb-3 break-words">
                                            {{ $occurrence->description }}
                                        </p>

                                        @if ($occurrence->actions_taken)
                                            <div class="mt-3 pt-2 border-t border-white/5">
                                                <span
                                                    class="block text-[8px] uppercase font-black tracking-widest text-gold-500/60 mb-1">
                                                    Providências Tomadas
                                                </span>
                                                <p class="text-white/60 italic text-xs break-words">
                                                    {{ $occurrence->actions_taken }}
                                                </p>
                                            </div>
                                        @endif

                                        <form action="{{ route('occurrences.destroy', $occurrence->id) }}" method="POST"
                                            class="form-delete hidden md:block absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-200 transform scale-95 group-hover:scale-100 z-20">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center justify-center w-8 h-8 rounded-xl bg-slate-900/40 backdrop-blur-sm border border-white/10 text-slate-300 hover:text-rose-400 hover:bg-rose-500/20 hover:border-rose-500/30 transition-all shadow-md"
                                                title="Excluir Ocorrência">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div
                                        class="col-span-2 py-12 text-center border-2 border-dashed border-white/10 rounded-3xl">
                                        <p class="text-white/30 italic font-serif text-lg">
                                            Nenhuma ocorrência ou atendimento registrado para este aluno.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div
                            class="absolute -right-10 -bottom-10 text-white/[0.03] text-9xl font-classic pointer-events-none select-none">
                            REGISTRUM
                        </div>

                        @include('partials.modals.occurrence')

                    </div>

                    {{-- BLOCO: RELATOS DE PRECEPTORIA (Estilo Premium Escuro Nativo) --}}
                    <div
                        class="bg-navy-900 rounded-3xl p-8 shadow-2xl relative overflow-hidden border border-transparent transition-all">
                        <div class="relative z-10">

                            <div class="flex justify-between items-center mb-8">
                                <div>
                                    <h3 class="font-classic text-2xl text-gold-500 uppercase tracking-tight">
                                        Relatos de Preceptoria
                                    </h3>
                                    <p
                                        class="text-white/40 text-[10px] font-bold uppercase tracking-widest">
                                        {{ $bimester }}º Bimestre
                                    </p>
                                </div>
                                <button onclick="openPreceptoryModal()"
                                    class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-gold-500 hover:bg-white/10 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest">
                                        Novo Relato
                                    </span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @forelse($reports as $report)
                                    <div
                                        class="bg-white/5 border-l-4 border-gold-500 p-6 rounded-r-2xl hover:bg-white/[0.08] transition-colors">
                                        <div class="flex justify-between items-start mb-3">
                                            <span class="text-gold-500 font-bold text-[10px] uppercase tracking-widest">
                                                {{ $report->subject->name ?? 'Desenvolvimento Geral' }}
                                            </span>
                                            <span class="text-white/30 text-[10px] font-mono">
                                                {{ $report->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <p
                                            class="text-white/80 font-serif italic text-lg leading-relaxed">
                                            "{!! nl2br(e($report->content)) !!}"
                                        </p>
                                    </div>
                                @empty
                                    <div
                                        class="col-span-2 py-12 text-center border-2 border-dashed border-white/10 rounded-3xl">
                                        <p class="text-white/30 italic font-serif text-lg">
                                            Nenhum relato lançado neste bimestre.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Marca d'água decorativa de fundo --}}
                        <div
                            class="absolute -right-10 -bottom-10 text-white/[0.03] text-9xl font-classic pointer-events-none select-none">
                            MATER
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('partials.modals.preceptory')
        @include('partials.modals.concept')
    @endif

    <script>
        // Funções de Controle com animação refinada para os Modais
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    const content = modal.querySelector('#modal-content') || modal.querySelector('.modal-content');
                    if (content) {
                        content.classList.remove('scale-95', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }
                }, 10);
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                const content = modal.querySelector('#modal-content') || modal.querySelector('.modal-content');
                if (content) {
                    content.classList.remove('scale-100', 'opacity-100');
                    content.classList.add('scale-95', 'opacity-0');
                }
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 200);
            }
        }

        function openPreceptoryModal() {
            openModal('modal-preceptoria');
        }

        function openConceptModal() {
            openModal('modal-conceito');
        }

        // Função auxiliar para contrair/expandir card de notas de forma fluida
        function toggleGradesCard() {
            const body = document.getElementById('grades-body');
            const icon = document.getElementById('grades-icon');
            if (body && icon) {
                body.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            }
        }
    </script>
@endsection
