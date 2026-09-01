@extends('layouts.app')

@section('content')
    <div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8 text-slate-100 min-h-screen">

        {{-- TOPO: Identidade Visual & Informações do Aluno --}}
        <div
            class="flex flex-col lg:flex-row lg:items-center lg:justify-between bg-[#0f1a34] p-6 rounded-3xl border border-amber-500/10 shadow-2xl gap-6">
            <div class="flex items-center gap-5">
                {{-- Avatar Premium com Iniciais --}}
                <div
                    class="w-16 h-16 bg-[#0b1329] rounded-2xl flex items-center justify-center border-b-2 border-amber-500 shadow-lg shrink-0">
                    <span class="text-amber-400 font-serif text-3xl font-bold">
                        {{ mb_substr($student->name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <h1 class="font-serif text-2xl text-amber-400 tracking-wide">Matriz de Avaliação</h1>
                    <p class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-widest font-mono">Parecer Descritivo do
                        Aluno</p>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-black text-slate-200 uppercase tracking-wider">{{ $student->name }}</span>
                        <span
                            class="text-[10px] bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2.5 py-0.5 rounded-full uppercase font-bold">
                            {{ $bimester }}° Bimestre / {{ $year }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Seletor de Bimestre Rápido --}}
            <div
                class="flex items-center gap-3 bg-[#0b1329] p-3 rounded-2xl border border-slate-800 self-start lg:self-auto shadow-inner">
                <span class="text-[10px] text-slate-400 uppercase font-black tracking-widest pl-2">Mudar Época:</span>
                <form method="GET" action="{{ url()->current() }}" class="inline-block">
                    <select name="bimester" onchange="this.form.submit()"
                        class="bg-transparent text-xs text-amber-400 font-bold border-none outline-none focus:ring-0 cursor-pointer pr-8 py-0">
                        @foreach ([1, 2, 3, 4] as $b)
                            <option value="{{ $b }}" class="bg-[#0b1329] text-slate-300 font-bold"
                                {{ $b == $bimester ? 'selected' : '' }}>
                                {{ $b }}º Bimestre
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        {{-- Retornos de Status e Erros --}}
        @if (session('success'))
            <div
                class="p-4 bg-emerald-950/40 border border-emerald-500/30 text-emerald-400 text-xs font-bold uppercase tracking-wider rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="p-4 bg-rose-950/40 border border-rose-500/30 text-rose-400 text-xs font-bold uppercase tracking-wider rounded-2xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulário Principal --}}
        <form method="POST" action="{{ route('descriptive-evaluation.update', $student) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <input type="hidden" name="bimester" value="{{ $bimester }}">
            <input type="hidden" name="year" value="{{ $year }}">

            {{-- 1. LOOP DAS PERGUNTAS VINCULADAS ÀS DISCIPLINAS (Matriz Tipo 1) --}}
            @foreach ($groupedQuestions as $subjectId => $questions)
                {{-- Garantimos que só entra no bloco se o subjectId for válido e não-vazio --}}
                @if ($subjectId !== null && $subjectId !== '' && $subjectId !== 0)
                    @php $subject = $subjects->get($subjectId); @endphp

                    <div class="bg-[#0f1a34] rounded-3xl border border-amber-500/10 shadow-2xl overflow-hidden mb-8">

                        {{-- Título da Disciplina --}}
                        <div class="p-5 bg-[#0b1329] border-b border-amber-500/10">
                            <h2 class="font-serif text-amber-400 font-bold text-sm md:text-base uppercase tracking-wide">
                                RENDIMENTO / DESEMPENHO: {{ $subject?->name ?? 'Disciplina' }}
                            </h2>
                        </div>

                        {{-- Linhas de Perguntas --}}
                        <div class="divide-y divide-slate-800/60">
                            @foreach ($questions as $q)
                                <div
                                    class="p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 hover:bg-[#13203e]/40 transition-colors">

                                    {{-- Texto da Pergunta --}}
                                    <div class="lg:max-w-xl">
                                        <p class="text-sm text-slate-200 font-medium leading-relaxed">
                                            {{ $q->question_text }}
                                        </p>
                                    </div>

                                    {{-- Opções de Seleção (Estrutura Isolada de Input e Label) --}}
                                    <div class="grid grid-cols-3 gap-2 w-full lg:w-[450px] shrink-0">
                                        @foreach ([
            'optimal' => ['label' => 'Muito Bem', 'color' => 'peer-checked:bg-emerald-500/20 peer-checked:text-emerald-400 peer-checked:border-emerald-500'],
            'partial' => ['label' => 'Em Parte', 'color' => 'peer-checked:bg-amber-500/20 peer-checked:text-amber-400 peer-checked:border-amber-500'],
            'critical' => ['label' => 'Não', 'color' => 'peer-checked:bg-rose-500/20 peer-checked:text-rose-400 peer-checked:border-rose-500'],
        ] as $optionKey => $optionData)
                                            @php
                                                // ID composto 100% único
                                                $inputUniqueId = "sub_{$subjectId}_q_{$q->id}_{$optionKey}";
                                                $isChecked = false;
                                                if (isset($existingRatings) && is_array($existingRatings)) {
                                                    $isChecked =
                                                        (string) ($existingRatings[$q->id] ?? '') ===
                                                        (string) $optionKey;
                                                }
                                            @endphp
                                            <div class="relative">
                                                <input type="radio" id="{{ $inputUniqueId }}"
                                                    name="ratings[{{ $q->id }}]" value="{{ $optionKey }}"
                                                    class="sr-only peer" {{ $isChecked ? 'checked' : '' }}>

                                                <label for="{{ $inputUniqueId }}"
                                                    class="block cursor-pointer select-none py-3 px-2 text-center rounded-xl bg-[#0b1329] border border-slate-800 text-[10px] uppercase tracking-wider font-bold text-slate-400 transition-all hover:bg-slate-800/30 hover:text-slate-200 {{ $optionData['color'] }}">
                                                    {{ $optionData['label'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- 2. BLOCO DAS PERGUNTAS GERAIS / DE CONDUTA (Matriz Tipo 2) --}}
            @php
                $emptyQuestions = collect();
                if (isset($groupedQuestions[''])) {
                    $emptyQuestions = $emptyQuestions->merge($groupedQuestions['']);
                }
                if (isset($groupedQuestions[null])) {
                    $emptyQuestions = $emptyQuestions->merge($groupedQuestions[null]);
                }
            @endphp

            @if ($emptyQuestions->isNotEmpty())
                <div class="bg-[#0f1a34] rounded-3xl border border-amber-500/10 shadow-2xl overflow-hidden">

                    {{-- Título da Seção Geral --}}
                    <div class="p-5 bg-[#0b1329] border-b border-amber-500/10">
                        <h2 class="font-serif text-amber-400 font-bold text-sm md:text-base uppercase tracking-wide">
                            DESENVOLVIMENTO PESSOAL / COMPORTAMENTO E VIRTUDES
                        </h2>
                    </div>

                    {{-- Lista de Questões de Conduta --}}
                    <div class="divide-y divide-slate-800/60">
                        @foreach ($emptyQuestions as $q)
                            <div
                                class="p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 hover:bg-[#13203e]/40 transition-colors">

                                {{-- Texto da Pergunta --}}
                                <div class="lg:max-w-xl">
                                    <p class="text-sm text-slate-200 font-medium leading-relaxed">
                                        {{ $q->question_text }}
                                    </p>
                                </div>

                                {{-- Opções de Seleção de Conduta --}}
                                <div class="grid grid-cols-3 gap-2 w-full lg:w-[450px] shrink-0">
                                    @foreach ([
            'optimal' => ['label' => 'Sim / Sempre', 'color' => 'peer-checked:bg-emerald-500/20 peer-checked:text-emerald-400 peer-checked:border-emerald-500'],
            'partial' => ['label' => 'Às Vezes', 'color' => 'peer-checked:bg-amber-500/20 peer-checked:text-amber-400 peer-checked:border-amber-500'],
            'critical' => ['label' => 'Raramente', 'color' => 'peer-checked:bg-rose-500/20 peer-checked:text-rose-400 peer-checked:border-rose-500'],
        ] as $optionKey => $optionData)
                                        @php
                                            $behaviorUniqueId = "general_q_{$q->id}_{$optionKey}";
                                            $isChecked = false;
                                            if (isset($existingRatings) && is_array($existingRatings)) {
                                                $isChecked =
                                                    (string) ($existingRatings[$q->id] ?? '') === (string) $optionKey;
                                            }
                                        @endphp
                                        <div class="relative">
                                            <input type="radio" id="{{ $behaviorUniqueId }}"
                                                name="ratings[{{ $q->id }}]" value="{{ $optionKey }}"
                                                class="sr-only peer" {{ $isChecked ? 'checked' : '' }}>

                                            <label for="{{ $behaviorUniqueId }}"
                                                class="block cursor-pointer select-none py-3 px-2 text-center rounded-xl bg-[#0b1329] border border-slate-800 text-[10px] uppercase tracking-wider font-bold text-slate-400 transition-all hover:bg-slate-800/30 hover:text-slate-200 {{ $optionData['color'] }}">
                                                {{ $optionData['label'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- BOTÕES DE AÇÃO NA BASE DA PÁGINA --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">

                {{-- Link de Voltar --}}
                <a href="{{ route('students.show', [$student, 'bimester' => $bimester]) }}"
                    class="px-6 py-3.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700 transition-all text-xs uppercase tracking-widest font-black inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar ao Perfil
                </a>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">

                    {{-- Botão de Eliminar Avaliação --}}
                    @if (!empty($existingRatings))
                        <form method="POST" action="{{ route('descriptive-evaluation.destroy', $student) }}"
                            onsubmit="return confirm('Tem certeza que deseja apagar os dados desta avaliação?');">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="bimester" value="{{ $bimester }}">
                            <input type="hidden" name="year" value="{{ $year }}">

                            <button type="submit"
                                class="bg-rose-950/60 hover:bg-rose-900 border border-rose-800/50 text-rose-300 font-bold uppercase tracking-widest px-6 py-4 rounded-xl text-xs transition-all cursor-pointer">
                                Eliminar Avaliação
                            </button>
                        </form>
                    @endif

                    {{-- Botão Principal de Submissão --}}
                    <button type="submit"
                        class="w-full sm:w-auto bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black uppercase tracking-widest px-10 py-4 rounded-xl transition-all shadow-lg shadow-amber-500/10 hover:shadow-amber-500/20 text-xs active:scale-[0.98] cursor-pointer">
                        Salvar Avaliação Descritiva
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
