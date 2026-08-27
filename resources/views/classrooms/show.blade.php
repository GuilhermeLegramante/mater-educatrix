@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">
        <div class="flex justify-between items-end border-b border-slate-200 pb-6 mb-10">
            <div>
                <h1 class="font-classic text-4xl text-navy-900 transition-colors">{{ $classroom->name }}</h1>
                <p class="text-gold-600 font-bold uppercase tracking-widest text-xs mt-1">
                    Gestão de Turma • Ano Letivo {{ $classroom->year }}
                </p>
            </div>
            <div class="flex gap-3">
                <button onclick="openModal('modal-enroll')"
                    class="bg-navy-900 text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-navy-900/20">
                    + Matricular Aluno
                </button>
            </div>
        </div>

        @include('partials.modals.enrollment', [
            'classroom' => $classroom,
            'students' => $students,
            'classrooms' => $classrooms,
        ])

        @include('partials.modals.unenrollment', [
            'classroom' => $classroom,
        ])

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden backdrop-blur-sm">

                    <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-navy-900 uppercase text-xs tracking-widest">Alunos
                        </h3>
                        <div class="flex gap-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase">Bimestre
                                Ativo:</span>
                            <span class="text-[10px] font-black text-gold-600 uppercase">
                                {{ $settings?->active_bimester ?? 1 }}º Bimestre
                            </span>
                        </div>
                    </div>

                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black">
                            <tr>
                                <th class="px-8 py-4">Aluno</th>
                                <th class="px-8 py-4 text-center">Status</th>
                                <th class="px-8 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($classroom->students as $student)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <!-- Coluna: Nome e Matrícula do Aluno -->
                                    <td class="px-8 py-4">
                                        <span class="font-bold text-navy-900 group-hover:text-gold-500 transition-colors">
                                            {{ $student->name }}
                                        </span>
                                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">
                                            {{ $student->registration_number }}
                                        </p>
                                    </td>

                                    <!-- Coluna: Status -->
                                    <td class="px-8 py-4 text-center">
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter bg-green-100 text-green-700">
                                            Ativo
                                        </span>
                                    </td>

                                    <!-- Coluna: Ações (Botões Lado a Lado) -->
                                    <td class="px-8 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Botão 1: Detalhes -->
                                            <a href="{{ route('students.show', $student) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-50 text-navy-900 hover:bg-gold-50 rounded-xl transition-all border border-slate-200 font-bold text-[10px] uppercase tracking-widest group-hover:border-gold-300">
                                                Detalhes
                                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-gold-500 transition-colors"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>

                                            <!-- Botão 2: Desmatricular -->
                                            <button type="button"
                                                onclick="confirmUnenroll('{{ route('classrooms.unenroll', [$classroom, $student]) }}', '{{ $student->name }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-xl transition-all border border-rose-200 font-bold text-[10px] uppercase tracking-widest"
                                                title="Desmatricular Aluno">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6h12a6 6 0 00-6-6zM21 12h-6" />
                                                </svg>
                                                Desmatricular
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div
                    class="bg-navy-900 text-white p-8 rounded-3xl shadow-xl border-b-8 border-gold-500 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-classic text-xl text-gold-500 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Grade Curricular
                            </h3>
                            <button onclick="openModal('modal-curriculum')"
                                class="text-[10px] bg-white/10 hover:bg-gold-600 hover:text-navy-950 px-2.5 py-1.5 rounded-lg font-bold uppercase tracking-wider transition-all border border-white/10">
                                Configurar
                            </button>
                        </div>

                        <ul class="space-y-4">
                            @forelse ($classroom->subjects as $subject)
                                <li class="flex justify-between items-center border-b border-white/10 pb-3">
                                    <span class="font-bold text-sm uppercase tracking-tight">{{ $subject->name }}</span>
                                    <span
                                        class="bg-gold-500/10 text-gold-500 text-[10px] font-black px-2 py-1 rounded border border-gold-500/20">
                                        {{ $subject->pivot->workload ?? 0 }}H
                                    </span>
                                </li>
                            @empty
                                <li class="text-xs text-slate-400 italic py-2">Nenhuma disciplina na grade desta turma.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-5 text-8xl font-classic pointer-events-none">MATER</div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-curriculum"
        class="fixed inset-0 z-50 hidden bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
        <div
            class="bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform scale-95 transition-transform duration-300">

            <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-navy-900">Definir Grade Curricular</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Defina as cargas horárias das disciplinas para esta turma.</p>
                </div>
                <button onclick="closeModal('modal-curriculum')"
                    class="p-2 text-slate-400 hover:text-slate-600 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('classrooms.curriculum.update', $classroom) }}" method="POST"
                class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                @csrf
                @method('PUT')

                <div class="space-y-3">
                    @foreach ($allSubjects as $allSub)
                        @php
                            // Procura se a disciplina já está associada para capturar o workload atual dela nesta turma
                            $currentWorkload =
                                $classroom->subjects->firstWhere('id', $allSub->id)->pivot->workload ?? '';
                        @endphp
                        <div
                            class="flex items-center justify-between p-3 rounded-2xl border border-slate-100 bg-slate-50/30 hover:border-slate-200 transition-colors">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-navy-900">{{ $allSub->name }}</span>
                                <span class="text-[10px] text-slate-400 uppercase tracking-tight">Carga Horária
                                    Semanal</span>
                            </div>

                            <div class="w-32 flex items-center gap-2">
                                <input type="number" name="subjects[{{ $allSub->id }}][workload]"
                                    value="{{ $currentWorkload }}" placeholder="0" min="0" max="400"
                                    class="w-full text-center font-bold text-sm bg-white border border-slate-200 rounded-xl px-2 py-2 text-navy-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                                <span class="text-xs font-black text-slate-400">H</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeModal('modal-curriculum')"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold uppercase text-slate-500 hover:bg-slate-50 transition-all">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-navy-900 text-white text-xs font-bold uppercase tracking-wider hover:bg-gold-600 transition-all shadow-md">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
