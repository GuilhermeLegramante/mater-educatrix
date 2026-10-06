@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto animate-fade-in">
        <nav class="flex mb-4 text-slate-400 text-[10px] uppercase font-black tracking-widest">
            <a href="{{ route('questions.index') }}" class="hover:text-navy-900 transition-colors">Questões</a>
            <span class="mx-2">/</span>
            <span class="text-gold-600">Editar Questão</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
            <div class="bg-navy-900 p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="font-classic text-3xl">Editar Questão</h2>
                    <p class="text-gold-500 text-xs font-bold uppercase tracking-[0.2em] mt-1">
                        Questão #{{ $question->order_index }}
                    </p>
                </div>
                <div class="absolute right-[-20px] top-[-20px] text-white/[0.05] text-8xl font-classic select-none">
                    MATER
                </div>
            </div>

            <form action="{{ route('questions.update', $question->id) }}" method="POST"
                class="p-10 space-y-8 text-slate-700">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Turma --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                            Turma / Ano Letivo
                        </label>
                        <select name="classroom_id"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900">
                            <option value="">Geral (Aplicável a todas as turmas)</option>
                            @foreach ($classrooms as $c)
                                <option value="{{ $c->id }}"
                                    {{ old('classroom_id', $question->classroom_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Disciplina --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                            Disciplina
                        </label>
                        <select name="subject_id"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900">
                            <option value="">Comportamental / Nenhuma</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $question->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Enunciado da Questão --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                        Enunciado / Descrição da Questão
                    </label>
                    <textarea name="statement" rows="4" required
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 resize-none">{{ old('statement', $question->statement) }}</textarea>
                </div>

                {{-- Ordem na Planilha --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                            Ordem na Planilha
                        </label>
                        <input type="number" name="order_index" value="{{ old('order_index', $question->order_index) }}"
                            required
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 text-center">
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <a href="{{ route('questions.index') }}"
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
