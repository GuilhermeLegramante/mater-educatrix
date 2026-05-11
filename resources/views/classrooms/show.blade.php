@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">
        <div class="flex justify-between items-end border-b border-slate-200 pb-6">
            <div>
                <h1 class="font-classic text-4xl text-navy-900">{{ $classroom->name }}</h1>
                <p class="text-gold-600 font-bold uppercase tracking-widest text-xs">
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-navy-900 uppercase text-xs tracking-widest">Quadro de Rendimento</h3>
                        <div class="flex gap-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase">Bimestre Ativo:</span>
                            <span class="text-[10px] font-black text-gold-600 uppercase">1º Bimestre</span>
                        </div>
                    </div>

                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black">
                            <tr>
                                <th class="px-8 py-4">Estudante</th>
                                <th class="px-8 py-4 text-center">Status</th>
                                <th class="px-8 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($classroom->students as $student)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-4">
                                        <span class="font-bold text-navy-900">{{ $student->name }}</span>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $student->registration_number }}
                                        </p>
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter bg-green-100 text-green-700">
                                            Ativo
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <a href="{{ route('students.show', $student) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 text-navy-900 hover:bg-gold-50 rounded-xl transition-all border border-slate-200 font-bold text-[10px] uppercase tracking-widest group-hover:border-gold-300">
                                            Detalhes
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
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
                        <h3 class="font-classic text-xl text-gold-500 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Grade Curricular
                        </h3>
                        <ul class="space-y-4">
                            @foreach ($classroom->subjects as $subject)
                                <li class="flex justify-between items-center border-b border-white/10 pb-3">
                                    <span class="font-bold text-sm uppercase tracking-tight">{{ $subject->name }}</span>
                                    <span
                                        class="bg-gold-500/10 text-gold-500 text-[10px] font-black px-2 py-1 rounded border border-gold-500/20">
                                        {{ $subject->workload }}H
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-5 text-8xl font-classic pointer-events-none">LEX</div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-enroll"
        class="fixed inset-0 bg-navy-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 modal-backdrop">
        <div
            class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl modal-content transform transition-all scale-95 opacity-0">
            <h3 class="font-classic text-2xl text-navy-900 mb-2">Matricular Estudante</h3>
            <p class="text-slate-400 text-xs mb-6 uppercase font-bold tracking-widest">Vincular novo registro à turma</p>

            <form action="{{ route('classrooms.enroll', $classroom) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Selecione o Aluno</label>
                    <select name="student_id" required
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-gold-500 font-bold text-navy-900">
                        <option value="" disabled selected>Escolha um nome...</option>
                        @foreach ($availableStudents as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="w-full bg-gold-500 text-navy-900 font-black py-4 rounded-xl uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20">
                    Confirmar Matrícula
                </button>
            </form>
        </div>
    </div>
@endsection
