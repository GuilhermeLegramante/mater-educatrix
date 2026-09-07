@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto" x-data="{
        openModal: false,
        activeStudentId: null,
        activeStudentName: '',
        adaptedMaxScore: '',
        adaptedStudentScore: '',
        standardMaxScore: {{ $evaluation->max_score }},
    
        // Função para abrir o modal para um aluno específico
        openAdaptedModal(id, name) {
            this.activeStudentId = id;
            this.activeStudentName = name;
            this.adaptedMaxScore = '';
            this.adaptedStudentScore = '';
            this.openModal = true;
        },
    
        // Calcula a equivalência proporcional
        get equivalentScore() {
            let max = parseFloat(this.adaptedMaxScore);
            let score = parseFloat(this.adaptedStudentScore);
    
            if (!max || !score || max <= 0) return 0;
    
            let result = (score * this.standardMaxScore) / max;
            return Math.min(result, this.standardMaxScore).toFixed(1);
        },
    
        // Aplica o valor calculado no input do aluno
        applyScore() {
            if (this.activeStudentId) {
                let input = document.getElementById('score_input_' + this.activeStudentId);
                if (input) {
                    input.value = this.equivalentScore;
                }
            }
            this.openModal = false;
        }
    }">
        <div class="mb-8">
            <h2 class="font-classic text-2xl text-navy-900 uppercase tracking-widest">
                {{ $evaluation->title }}</h2>
            <p class="text-gold-500 text-xs font-bold uppercase tracking-[0.2em] mt-1">
                {{ $classroom ? "Turma: {$classroom->name}" : 'Definição de Atividade Acadêmica' }}
            </p>
            <p class="text-slate-500">Disciplina: <span
                    class="font-bold text-navy-900">{{ $evaluation->subject->name }}</span></p>
        </div>

        <form action="{{ route('grades.store', [$classroom->id, $evaluation->id]) }}" method="POST"
            class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">
            @csrf
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Aluno</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 w-64 text-center">
                            Pontos Obtidos (Máx: {{ $evaluation->max_score }})
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($students as $student)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-navy-900">
                                {{ $student->name }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <input type="number" id="score_input_{{ $student->id }}"
                                        name="scores[{{ $student->id }}]"
                                        value="{{ $evaluation->grades->where('student_id', $student->id)->first()?->score ?? '' }}"
                                        step="0.1" max="{{ $evaluation->max_score }}" required
                                        class="w-full bg-slate-50 border-slate-200 rounded-lg focus:ring-gold-500 focus:border-gold-500 font-bold text-center py-2 text-sm">

                                    {{-- BOTÃO PARA ABRIR O MODAL DA PROVA ADAPTADA --}}
                                    <button type="button"
                                        @click="openAdaptedModal({{ $student->id }}, '{{ addslashes($student->name) }}')"
                                        title="Calcular Prova Adaptada"
                                        class="p-2 bg-slate-100 hover:bg-gold-500 text-slate-600 hover:text-navy-950 rounded-lg transition-colors cursor-pointer shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-6 bg-slate-50 flex justify-end">
                <button type="submit"
                    class="bg-gold-500 text-navy-950 px-8 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 cursor-pointer">
                    Salvar Notas
                </button>
            </div>
        </form>

        {{-- MODAL DE CÁLCULO DE PROVA ADAPTADA --}}
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm" @click="openModal = false">
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white border border-slate-200 shadow-2xl rounded-3xl sm:w-full">

                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                        <div>
                            <h3 class="font-classic text-lg font-bold text-navy-900 uppercase tracking-wider">Calculadora de
                                Prova Adaptada</h3>
                            <p class="text-xs text-slate-400 mt-0.5" x-text="`Aluno: ${activeStudentName}`"></p>
                        </div>
                        <button type="button" @click="openModal = false"
                            class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-1">
                                Máximo de Scores/Questões da Prova Adaptada
                            </label>
                            <input type="number" x-model="adaptedMaxScore" placeholder="Ex: 30"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-navy-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-1">
                                Scores/Pontos Obtidos pelo Aluno
                            </label>
                            <input type="number" x-model="adaptedStudentScore" placeholder="Ex: 24"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-navy-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500">
                        </div>

                        {{-- RESULTADO DA CONVERSÃO --}}
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-center">
                            <span class="block text-[10px] uppercase font-black tracking-widest text-slate-400">
                                Nota Proporcional Equivalente (Máx: {{ $evaluation->max_score }})
                            </span>
                            <span class="text-3xl font-black text-gold-600 mt-1 block" x-text="equivalentScore"></span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-6">
                        <button type="button" @click="openModal = false"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-100 transition-colors">
                            Cancelar
                        </button>
                        <button type="button" @click="applyScore()"
                            class="bg-gold-500 text-navy-950 px-6 py-2.5 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20">
                            Aplicar Nota
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
