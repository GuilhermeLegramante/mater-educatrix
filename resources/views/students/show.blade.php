@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-fade-in">

        <div class="flex justify-between items-start">
            <div class="flex items-center gap-6">
                <div
                    class="w-20 h-20 bg-navy-900 rounded-2xl flex items-center justify-center border-b-4 border-gold-500 shadow-xl">
                    <span class="text-gold-500 font-classic text-4xl">A</span>
                </div>
                <div>
                    <p class="text-gold-600 font-bold uppercase tracking-widest text-[10px] mb-1">Desempenho Acadêmico</p>
                    <h1 class="font-classic text-5xl text-navy-900 uppercase tracking-tight">{{ $student->name }}</h1>
                    <p class="text-slate-400 font-mono text-xs uppercase">Matrícula: {{ $student->registration_number }}</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button onclick="window.print()"
                    class="px-6 py-3 border-2 border-slate-200 rounded-xl font-black text-[10px] uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all">
                    Imprimir Ficha
                </button>
                <button onclick="openConceptModal({{ $student->id }})"
                    class="px-6 py-3 bg-gold-500 text-navy-900 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20">
                    Lançar Conceito
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <div class="space-y-4">
                <h3 class="font-bold text-navy-900 uppercase text-[10px] tracking-widest px-2">Conceito por Disciplina</h3>
                @foreach ($activeClassroom->subjects as $subject)
                    @php
                        $concept = $student->getConsolidatedConcept($activeClassroom->id, $subject->id, 1); // 1 = 1º Bimestre
                    @endphp
                    <div
                        class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex justify-between items-center group hover:border-gold-500 transition-all">
                        <div>
                            <span
                                class="block font-black text-navy-900 text-[11px] uppercase tracking-tight">{{ $subject->name }}</span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase">Média Bimestral</span>
                        </div>
                        <div
                            class="w-10 h-10 {{ $concept == 'A' ? 'bg-navy-900 text-gold-500' : 'bg-slate-100 text-slate-600' }} rounded-xl flex items-center justify-center font-black shadow-sm group-hover:scale-110 transition-transform">
                            {{ $concept }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-400 text-[9px] uppercase font-black">
                            <tr>
                                <th class="px-8 py-4">Avaliação / Disciplina</th>
                                <th class="px-8 py-4 text-center">Score (Acertos)</th>
                                <th class="px-8 py-4 text-center">Aproveitamento</th>
                                <th class="px-8 py-4 text-center">Conceito</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($student->grades as $grade)
                                @php
                                    $perc = ($grade->score / $grade->evaluation->max_score) * 100;
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-4">
                                        <span
                                            class="font-bold text-navy-900 text-sm block">{{ $grade->evaluation->title }}</span>
                                        <span
                                            class="text-[10px] text-gold-600 font-bold uppercase tracking-widest">{{ $grade->evaluation->subject->name }}</span>
                                    </td>
                                    <td class="px-8 py-4 text-center font-mono text-xs text-slate-600">
                                        <span class="font-bold text-navy-900">{{ $grade->score }}</span>
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
                                            {{ $student->getConsolidatedConcept($grade->evaluation->classroom_id, $grade->evaluation->subject_id, $grade->evaluation->bimester) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-8 bg-navy-900 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-8">
                            <div>
                                <h3 class="font-classic text-2xl text-gold-500 uppercase tracking-tight">Relatos de
                                    Preceptoria</h3>
                                <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest">Acompanhamento
                                    Qualitativo e Virtudes</p>
                            </div>
                            <button onclick="openPreceptoryModal()"
                                class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-gold-500 hover:bg-white/10 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-widest">Novo Relato</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($student->preceptoryReports as $report)
                                <div
                                    class="bg-white/5 border-l-4 border-gold-500 p-6 rounded-r-2xl hover:bg-white/[0.08] transition-colors">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="text-gold-500 font-bold text-[10px] uppercase tracking-widest">
                                            {{ $report->subject->name ?? 'Desenvolvimento Geral' }}
                                        </span>
                                        <span
                                            class="text-white/30 text-[10px] font-mono">{{ $report->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <p class="text-white/80 font-serif italic text-lg leading-relaxed">
                                        "{{ $report->content }}"
                                    </p>
                                    <div class="mt-4 flex justify-end">
                                        <span
                                            class="text-[9px] text-white/20 font-bold uppercase tracking-tighter italic">Bimestre:
                                            {{ $report->bimester }}º</span>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="col-span-2 py-12 text-center border-2 border-dashed border-white/10 rounded-3xl">
                                    <p class="text-white/30 italic font-serif text-lg">Nenhum registro qualitativo de
                                        preceptoria até o momento.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div
                        class="absolute -right-10 -bottom-10 text-white/[0.03] text-9xl font-classic pointer-events-none select-none">
                        MATER
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-preceptoria"
        class="fixed inset-0 bg-navy-900/90 backdrop-blur-md hidden z-50 flex items-center justify-center p-4 modal-backdrop">
        <div
            class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden modal-content transform transition-all scale-95 opacity-0">
            <div class="p-6 bg-navy-900 text-white flex justify-between items-center">
                <h3 class="font-classic text-xl text-gold-500">Novo Relato de Preceptoria</h3>
                <button onclick="closeModal('modal-preceptoria')"
                    class="text-white/50 hover:text-white text-2xl">&times;</button>
            </div>
            <form action="{{ route('preceptory.store', $activeClassroom) }}" method="POST" class="p-8 space-y-5">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <div class="grid grid-cols-2 gap-4">
                    <select name="bimester"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold">
                        <option value="1">1º Bimestre</option>
                        <option value="2">2º Bimestre</option>
                        <option value="3">3º Bimestre</option>
                        <option value="4">4º Bimestre</option>
                    </select>
                    <select name="subject_id"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold">
                        <option value="">Desenvolvimento Geral</option>
                        @foreach ($activeClassroom->subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <textarea name="content" rows="4" required
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-serif italic text-lg"
                    placeholder="Descreva as virtudes e o progresso..."></textarea>
                <button type="submit"
                    class="w-full bg-gold-500 text-navy-900 font-black py-4 rounded-xl uppercase tracking-widest">Gravar
                    Relato</button>
            </form>
        </div>
    </div>

    <div id="modal-conceito"
        class="fixed inset-0 bg-navy-900/90 backdrop-blur-md hidden z-50 flex items-center justify-center p-4 modal-backdrop">
        <div
            class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden modal-content transform transition-all scale-95 opacity-0">
            <div class="p-6 bg-gold-500 text-navy-900 flex justify-between items-center">
                <h3 class="font-classic text-xl font-bold uppercase">Lançar Conceito Final</h3>
                <button onclick="closeModal('modal-conceito')"
                    class="text-navy-900/50 hover:text-navy-900 text-2xl">&times;</button>
            </div>
            <form action="{{ route('concepts.update', $activeClassroom) }}" method="POST" class="p-8 space-y-5">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <select name="subject_id" required
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold">
                    @foreach ($activeClassroom->subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                <div class="grid grid-cols-2 gap-4">
                    <select name="bimester"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold">
                        <option value="1">1º Bimestre</option>
                        <option value="2">2º Bimestre</option>
                    </select>
                    <select name="concept"
                        class="w-full bg-navy-900 text-gold-500 border-none rounded-xl px-4 py-3 font-black text-center">
                        <option value="A">CONCEITO A</option>
                        <option value="B">CONCEITO B</option>
                        <option value="C">CONCEITO C</option>
                        <option value="D">CONCEITO D</option>
                    </select>
                </div>
                <button type="submit"
                    class="w-full bg-navy-900 text-white font-black py-4 rounded-xl uppercase tracking-widest">Confirmar
                    Conceito</button>
            </form>
        </div>
    </div>

    <script>
        // Funções de Controle (Injetadas localmente para segurança)
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    const content = modal.querySelector('.modal-content');
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                const content = modal.querySelector('.modal-content');
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
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
    </script>
@endsection
