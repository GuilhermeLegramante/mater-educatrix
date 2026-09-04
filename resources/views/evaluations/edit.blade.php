@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto animate-fade-in">
        <nav class="flex mb-4 text-slate-400 text-[10px] uppercase font-black tracking-widest">
            <a href="{{ route('evaluations.index') }}" class="hover:text-navy-900 transition-colors">Avaliações</a>
            <span class="mx-2">/</span>
            <span class="text-gold-600">Editar Avaliação</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
            <div class="bg-navy-900 p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="font-classic text-3xl">Editar Avaliação</h2>
                    <p class="text-gold-500 text-xs font-bold uppercase tracking-[0.2em] mt-1">
                        {{ $evaluation->title }}
                    </p>
                </div>
                <div class="absolute right-[-20px] top-[-20px] text-white/[0.05] text-8xl font-classic select-none">
                    MATER
                </div>
            </div>

            <form action="{{ route('evaluations.update', $evaluation->id) }}" method="POST"
                class="p-10 space-y-8 text-slate-700">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                        Turma / Ano Letivo
                    </label>
                    <select name="classroom_id" required
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900">
                        @foreach ($classrooms as $c)
                            <option value="{{ $c->id }}"
                                {{ old('classroom_id', $evaluation->classroom_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Disciplina</label>
                        <select name="subject_id" required
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900">
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $evaluation->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Título da
                            Avaliação</label>
                        <input type="text" name="title" value="{{ old('title', $evaluation->title) }}" required
                            placeholder="Ex: Simulado de Latim"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Bimestre</label>
                        <select name="bimester" required
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 text-center">
                            <option value="1" {{ old('bimester', $evaluation->bimester) == 1 ? 'selected' : '' }}>1º
                                Bimestre</option>
                            <option value="2" {{ old('bimester', $evaluation->bimester) == 2 ? 'selected' : '' }}>2º
                                Bimestre</option>
                            <option value="3" {{ old('bimester', $evaluation->bimester) == 3 ? 'selected' : '' }}>3º
                                Bimestre</option>
                            <option value="4" {{ old('bimester', $evaluation->bimester) == 4 ? 'selected' : '' }}>4º
                                Bimestre</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Peso</label>
                        <input type="number" name="weight" step="0.1"
                            value="{{ old('weight', $evaluation->weight) }}"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 text-center">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Pontuação
                            Máxima</label>
                        <input type="number" name="max_score" value="{{ old('max_score', $evaluation->max_score) }}"
                            required
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 text-center">
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <a href="{{ route('evaluations.index') }}"
                        class="w-1/3 bg-slate-100 text-slate-600 font-black py-5 rounded-2xl uppercase tracking-wider text-center hover:bg-slate-200 transition-all">
                        Cancelar
                    </a>

                    <button type="submit"
                        class="w-2/3 bg-navy-900 text-white font-black py-5 rounded-2xl uppercase tracking-[0.2em] hover:bg-gold-600 hover:text-navy-950 transition-all shadow-xl shadow-navy-900/10 flex items-center justify-center gap-3">
                        <span>Salvar Alterações</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
