@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto animate-fade-in">
        <nav class="flex mb-4 text-slate-400 text-[10px] uppercase font-black tracking-widest">
            <a href="{{ route('evaluations.index') }}" class="hover:text-navy-900 transition-colors">Avaliações</a>
            <span class="mx-2">/</span>
            <span class="text-gold-600">Nova Atividade</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
            <div class="bg-navy-900 p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="font-classic text-3xl">Nova Avaliação</h2>
                    <p class="text-gold-500 text-xs font-bold uppercase tracking-[0.2em] mt-1">
                        {{ $classroom ? "Turma: {$classroom->name}" : 'Definição de Atividade Acadêmica' }}
                    </p>
                </div>
                <div class="absolute right-[-20px] top-[-20px] text-white/[0.05] text-8xl font-classic select-none">
                    MATER
                </div>
            </div>

            <form action="{{ route('evaluations.store') }}" method="POST" class="p-10 space-y-8 text-slate-700"> @csrf

                {{-- Se não houver uma turma pré-selecionada, mostra o Select --}}
                @if (!$classroom)
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Turma
                            / Ano
                            Letivo</label>
                        <select name="classroom_id" required
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900">
                            <option value="">Selecione a turma de destino...</option>
                            @foreach ($classrooms as $c)
                                <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Disciplina</label>
                        <select name="subject_id" required
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900">
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Título
                            da
                            Avaliação</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            placeholder="Ex: Simulado de Latim"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 placeholder:text-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Bimestre</label>
                        <select name="bimester" required
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 text-center">

                            @php
                                // Define o bimestre selecionado: dá prioridade ao valor vindo da requisição/old,
                                // depois à variável $selectedBimester, ou pega o ativo da model SchoolSetting (com fallback para 1)
                                $currentBimester = old(
                                    'bimester',
                                    $selectedBimester ?? ($settings?->active_bimester ?? 1),
                                );
                            @endphp

                            @foreach (range(1, 4) as $bimester)
                                <option value="{{ $bimester }}" @selected($currentBimester == $bimester)>
                                    {{ $bimester }}º Bimestre
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Peso</label>
                        <input type="number" name="weight" step="0.1" value="{{ old('weight', '1.0') }}"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 text-center">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Pontuação
                            Máxima
                            (Score)</label>
                        <input type="number" name="max_score" value="{{ old('max_score', '100') }}" required
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 text-center">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full bg-navy-900 text-white font-black py-5 rounded-2xl uppercase tracking-[0.3em] hover:bg-gold-600 hover:text-navy-950 transition-all shadow-xl shadow-navy-900/10 group flex items-center justify-center gap-3">
                        <span>Confirmar e Lançar Notas</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                    <p class="text-center text-slate-400 text-[9px] mt-4 uppercase font-bold tracking-tight">
                        Ao confirmar, você será redirecionado para a planilha de scores dos alunos.
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection
