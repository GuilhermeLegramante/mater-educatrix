<div id="modal-conceito" onclick="if(event.target === this) closeModal('modal-conceito')"
    class="fixed inset-0 bg-navy-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-4 modal-backdrop transition-all">

    <div id="modal-content"
        class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-transparent overflow-hidden modal-content transform transition-all scale-95 opacity-0">

        <div class="p-6 bg-gold-500 text-navy-950 flex justify-between items-center shadow-md">
            <h3 class="font-classic text-xl font-black uppercase tracking-wide">Avaliação Qualitativa</h3>
            <button onclick="closeModal('modal-conceito')"
                class="text-navy-950/50 hover:text-navy-950 text-2xl transition-colors font-bold">&times;</button>
        </div>

        <form action="{{ route('concepts.update', $activeClassroom) }}" method="POST" class="p-8 space-y-5">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">

            {{-- SELEÇÃO DA DISCIPLINA --}}
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider">
                    Disciplina
                </label>
                <select name="subject_id" id="concept_subject_id" required onchange="updateSelectedQualitative()"
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold text-navy-900 outline-none focus:border-gold-500 transition-colors">
                    @foreach ($activeClassroom->subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $subject->id == $subjectId ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- SELEÇÃO DO BIMESTRE --}}
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider">
                        Período
                    </label>
                    <select name="bimester" id="concept_bimester" onchange="updateSelectedQualitative()"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold text-navy-900 outline-none focus:border-gold-500 transition-colors">
                        @for ($b = 1; $b <= 4; $b++)
                            <option value="{{ $b }}" {{ $bimester == $b ? 'selected' : '' }}>
                                {{ $b }}º Bimestre
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- SELEÇÃO DA AVALIAÇÃO QUALITATIVA (-1,0 a +1,0) --}}
                <div>
                    <label
                        class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider text-center">
                        Ajuste Qualitativo
                    </label>
                    <select name="qualitative_eval" id="qualitative_select"
                        class="w-full bg-navy-900 text-gold-500 border-none rounded-xl px-4 py-3 font-black text-center outline-none focus:ring-2 focus:ring-gold-500/50">
                        @for ($i = 10; $i >= -10; $i--)
                            @php $val = sprintf('%.1f', $i / 10); @endphp
                            <option value="{{ $val }}" class="bg-navy-900 text-gold-500 font-bold">
                                {{ $i > 0 ? '+' . $val : $val }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-navy-900 text-white font-black py-4 rounded-xl uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-navy-900/10">
                    Salvar Avaliação
                </button>
                <button type="button" onclick="closeModal('modal-conceito')"
                    class="w-full mt-3 text-slate-400 text-[10px] font-bold uppercase tracking-widest hover:text-navy-900 transition-colors">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

@php
    // Monta o mapa de pontuações qualitativas agrupadas por [subject_id][bimester]
    $qualitativeMap = [];
    if ($activeClassroom) {
        foreach ($activeClassroom->subjects as $subj) {
            for ($b = 1; $b <= 4; $b++) {
                $qualitativeMap[$subj->id][$b] = sprintf(
                    '%.1f',
                    $student->getQualitativeEval($activeClassroom->id, $subj->id, $b),
                );
            }
        }
    }
@endphp

<script>
    const studentQualitativeMap = @json($qualitativeMap);

    function updateSelectedQualitative() {
        const subjectSelect = document.getElementById('concept_subject_id');
        const bimesterSelect = document.getElementById('concept_bimester');
        const qualitativeSelect = document.getElementById('qualitative_select');

        if (!subjectSelect || !bimesterSelect || !qualitativeSelect) return;

        const subjectId = subjectSelect.value;
        const bimester = bimesterSelect.value;

        let currentVal = '0.0';
        if (studentQualitativeMap[subjectId] && studentQualitativeMap[subjectId][bimester] !== undefined) {
            currentVal = studentQualitativeMap[subjectId][bimester];
        }

        qualitativeSelect.value = currentVal;
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedQualitative();
    });
</script>
