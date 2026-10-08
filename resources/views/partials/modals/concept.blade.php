<div id="modal-conceito" onclick="if(event.target === this) closeModal('modal-conceito')"
    class="fixed inset-0 bg-navy-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-4 modal-backdrop transition-all">

    <div id="modal-content"
        class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-transparent overflow-hidden modal-content transform transition-all scale-95 opacity-0">

        {{-- CABEÇALHO DO MODAL --}}
        <div class="p-6 bg-gold-500 text-navy-950 flex justify-between items-center shadow-md">
            <div>
                <h3 class="font-classic text-xl font-black uppercase tracking-wide">Avaliação Qualitativa</h3>
                <p class="text-[10px] font-bold uppercase tracking-wider text-navy-950/70">Ajuste de -1,0 a +1,0 na nota
                    final</p>
            </div>
            <button onclick="closeModal('modal-conceito')"
                class="text-navy-950/50 hover:text-navy-950 text-2xl transition-colors font-bold cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('concepts.update', $activeClassroom) }}" method="POST" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">

            {{-- SELEÇÃO DA DISCIPLINA --}}
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 mb-1.5 block tracking-wider">
                    Disciplina
                </label>
                <select name="subject_id" id="concept_subject_id" required onchange="updateSelectedQualitative()"
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-2.5 font-bold text-navy-900 outline-none focus:border-gold-500 transition-colors">
                    @foreach ($activeClassroom->subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $subject->id == $subjectId ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- SELEÇÃO DO BIMESTRE --}}
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 mb-1.5 block tracking-wider">
                    Período
                </label>
                <select name="bimester" id="concept_bimester" onchange="updateSelectedQualitative()"
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-2.5 font-bold text-navy-900 outline-none focus:border-gold-500 transition-colors">
                    @for ($b = 1; $b <= 4; $b++)
                        <option value="{{ $b }}" {{ $bimester == $b ? 'selected' : '' }}>
                            {{ $b }}º Bimestre
                        </option>
                    @endfor
                </select>
            </div>

            {{-- CONTROLE DE AJUSTE COM BOTÕES + E - --}}
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider text-center">
                    Ajuste Qualitativo (Pontos)
                </label>

                <div class="flex items-center justify-center gap-3">
                    {{-- Botão Menos --}}
                    <button type="button" onclick="adjustQualitative(-0.1)"
                        class="w-12 h-12 bg-slate-100 hover:bg-slate-200 text-navy-900 rounded-2xl flex items-center justify-center font-black text-2xl transition-all active:scale-95 cursor-pointer select-none">
                        &minus;
                    </button>

                    {{-- Campo numérico escondido enviado no form --}}
                    <input type="hidden" name="qualitative_eval" id="qualitative_input" value="0.0">

                    {{-- Badge visual do valor do ajuste --}}
                    <div class="w-32 py-3 bg-navy-900 text-gold-500 rounded-2xl text-center shadow-md">
                        <span id="qualitative_display" class="font-mono text-2xl font-black">0,0</span>
                    </div>

                    {{-- Botão Mais --}}
                    <button type="button" onclick="adjustQualitative(0.1)"
                        class="w-12 h-12 bg-slate-100 hover:bg-slate-200 text-navy-900 rounded-2xl flex items-center justify-center font-black text-2xl transition-all active:scale-95 cursor-pointer select-none">
                        &#43;
                    </button>
                </div>
            </div>

            {{-- BOX DE PREVIEW DO CONCEITO E NOTA FINAL --}}
            <div class="bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block">
                        Simulação do Resultado
                    </span>

                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-slate-500 font-bold">Nota Base:</span>
                        <span id="preview_base_score" class="font-mono font-bold text-navy-900 text-xs">-</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 font-bold">Nota Final:</span>
                        <span id="preview_final_score" class="font-mono font-bold text-gold-600 text-sm">-</span>
                    </div>
                </div>

                {{-- Conceito Final resultante --}}
                <div class="text-center">
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block mb-0.5">
                        Conceito
                    </span>
                    <div id="preview_concept_badge"
                        class="w-12 h-12 rounded-2xl bg-navy-900 text-gold-500 font-black text-2xl flex items-center justify-center shadow-md">
                        -
                    </div>
                </div>
            </div>

            {{-- AÇÕES DO FORM --}}
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-navy-900 text-white font-black py-4 rounded-xl uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-navy-900/10 cursor-pointer">
                    Salvar Avaliação
                </button>
                <button type="button" onclick="closeModal('modal-conceito')"
                    class="w-full mt-3 text-slate-400 text-[10px] font-bold uppercase tracking-widest hover:text-navy-900 transition-colors cursor-pointer">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

@php
    // Estrutura com a nota base quantitativa e o ajuste qualitativo salvo [subject_id][bimester]
    $dataMap = [];
    if ($activeClassroom) {
        foreach ($activeClassroom->subjects as $subj) {
            for ($b = 1; $b <= 4; $b++) {
                $baseScore = $student->getBimesterScore($activeClassroom->id, $subj->id, $b);
                $qualitative = $student->getQualitativeEval($activeClassroom->id, $subj->id, $b);

                // Se houver ajuste salvo, removemos para saber a nota quantitativa base limpa
                $rawBaseScore = $baseScore !== null ? max(0.0, min(10.0, $baseScore - $qualitative)) : null;

                $dataMap[$subj->id][$b] = [
                    'base_score' => $rawBaseScore !== null ? round($rawBaseScore, 1) : null,
                    'qualitative' => round($qualitative, 1),
                ];
            }
        }
    }
@endphp

<script>
    const studentDataMap = @json($dataMap);

    // Função de conversão de nota em conceito (mesmo cálculo do backend)
    function calculateConceptFromScore(score) {
        if (score === null || isNaN(score)) return '-';
        if (score >= 9.0) return 'A';
        if (score >= 7.5) return 'B';
        if (score >= 6.0) return 'C';
        if (score >= 4.5) return 'D';
        if (score >= 3.0) return 'E';
        return 'F';
    }

    // Incrementa ou decrementa a pontuação qualitativa (-1.0 a +1.0)
    function adjustQualitative(delta) {
        const input = document.getElementById('qualitative_input');
        if (!input) return;

        let currentVal = parseFloat(input.value) || 0.0;
        let newVal = currentVal + delta;

        // Limita o valor rigorosamente entre -1.0 e +1.0
        newVal = Math.min(1.0, Math.max(-1.0, newVal));

        // Corrige imprecisões de ponto flutuante do JS
        input.value = newVal.toFixed(1);

        renderQualitativeAndPreview();
    }

    // Atualiza os seletores e carrega os dados armazenados
    function updateSelectedQualitative() {
        const subjectSelect = document.getElementById('concept_subject_id');
        const bimesterSelect = document.getElementById('concept_bimester');
        const input = document.getElementById('qualitative_input');

        if (!subjectSelect || !bimesterSelect || !input) return;

        const subjectId = subjectSelect.value;
        const bimester = bimesterSelect.value;

        let qualitativeVal = 0.0;
        if (studentDataMap[subjectId] && studentDataMap[subjectId][bimester]) {
            qualitativeVal = studentDataMap[subjectId][bimester].qualitative || 0.0;
        }

        input.value = qualitativeVal.toFixed(1);
        renderQualitativeAndPreview();
    }

    // Atualiza a interface gráfica, exibição formatada e a simulação de notas/conceito
    function renderQualitativeAndPreview() {
        const subjectSelect = document.getElementById('concept_subject_id');
        const bimesterSelect = document.getElementById('concept_bimester');
        const input = document.getElementById('qualitative_input');

        const display = document.getElementById('qualitative_display');
        const baseScoreSpan = document.getElementById('preview_base_score');
        const finalScoreSpan = document.getElementById('preview_final_score');
        const conceptBadge = document.getElementById('preview_concept_badge');

        if (!input || !display) return;

        const qualValue = parseFloat(input.value) || 0.0;

        // Exibição formatada do ajuste (+0,5 / -0,3 / 0,0)
        let formattedDisplay = qualValue.toFixed(1).replace('.', ',');
        if (qualValue > 0) {
            formattedDisplay = '+' + formattedDisplay;
        }
        display.innerText = formattedDisplay;

        // Cálculo da simulação em tempo real
        const subjectId = subjectSelect.value;
        const bimester = bimesterSelect.value;
        const entry = studentDataMap[subjectId] ? studentDataMap[subjectId][bimester] : null;

        if (entry && entry.base_score !== null) {
            const baseScore = parseFloat(entry.base_score);
            let finalScore = baseScore + qualValue;

            // Garante que a nota final fique no intervalo [0.0, 10.0]
            finalScore = Math.min(10.0, Math.max(0.0, finalScore));

            const concept = calculateConceptFromScore(finalScore);

            baseScoreSpan.innerText = baseScore.toFixed(1).replace('.', ',');
            finalScoreSpan.innerText = finalScore.toFixed(1).replace('.', ',');
            conceptBadge.innerText = concept;
        } else {
            baseScoreSpan.innerText = 'Sem notas';
            finalScoreSpan.innerText = '-';
            conceptBadge.innerText = '-';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedQualitative();
    });
</script>
