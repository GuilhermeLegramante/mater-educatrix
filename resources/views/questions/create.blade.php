@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto animate-fade-in">
        <nav class="flex mb-4 text-slate-400 text-[10px] uppercase font-black tracking-widest">
            <a href="{{ route('questions.index') }}" class="hover:text-navy-900 transition-colors">Questões</a>
            <span class="mx-2">/</span>
            <span class="text-gold-600">Nova Questão Descritiva</span>
        </nav>

        {{-- ALERTA GLOBAL DE ERROS DE VALIDAÇÃO --}}
        @if ($errors->any())
            <div class="mb-6 p-5 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-700 animate-fade-in">
                <div class="flex items-center mb-2 font-black text-xs uppercase tracking-wider">
                    <svg class="w-5 h-5 mr-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Por favor, corrija os erros abaixo para continuar:
                </div>
                <ul class="list-disc list-inside text-xs font-semibold space-y-1 ml-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
            <div class="bg-navy-900 p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="font-classic text-3xl">Cadastrar Questão</h2>
                    <p class="text-gold-500 text-xs font-bold uppercase tracking-[0.2em] mt-1">
                        Definição de Pergunta Descritiva
                    </p>
                </div>
                <div class="absolute right-[-20px] top-[-20px] text-white/[0.05] text-8xl font-classic select-none">
                    MATER
                </div>
            </div>

            <form action="{{ route('questions.store') }}" method="POST" class="p-10 space-y-8 text-slate-700">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Turma --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                            Turma / Ano Letivo
                        </label>
                        <select name="classroom_id"
                            class="w-full bg-slate-50 border-2 @error('classroom_id') border-rose-500 @else border-slate-100 @enderror rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900">
                            @foreach ($classrooms as $c)
                                <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('classroom_id')
                            <p class="text-rose-600 text-xs font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Disciplina --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                            Disciplina (Opcional)
                        </label>
                        <select name="subject_id"
                            class="w-full bg-slate-50 border-2 @error('subject_id') border-rose-500 @else border-slate-100 @enderror rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900">
                            <option value="">Comportamental / Nenhuma</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="text-rose-600 text-xs font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Enunciado da Questão --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                        Enunciado / Descrição da Questão
                    </label>
                    <textarea name="question_text" rows="4"
                        placeholder="Ex: Demonstra pontualidade, respeito e postura adequada durante as atividades em sala..."
                        class="w-full bg-slate-50 border-2 @error('question_text') border-rose-500 @else border-slate-100 @enderror rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 placeholder:text-slate-300 resize-none">{{ old('question_text') }}</textarea>
                    @error('question_text')
                        <p class="text-rose-600 text-xs font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ordem de Exibição --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                            Ordem na Planilha
                        </label>
                        <input type="number" name="order_index" value="{{ old('order_index', '1') }}"
                            class="w-full bg-slate-50 border-2 @error('order_index') border-rose-500 @else border-slate-100 @enderror rounded-2xl px-5 py-4 outline-none focus:border-gold-500 transition-all font-bold text-navy-900 text-center">
                        @error('order_index')
                            <p class="text-rose-600 text-xs font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full bg-navy-900 text-white font-black py-5 rounded-2xl uppercase tracking-[0.3em] hover:bg-gold-600 hover:text-navy-950 transition-all shadow-xl shadow-navy-900/10 group flex items-center justify-center gap-3">
                        <span>Salvar Questão</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
