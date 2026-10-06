@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <nav class="flex mb-2 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                    <a href="{{ route('questions.index') }}" class="hover:text-navy-900 transition-colors">Questões</a>
                    <span class="mx-2">/</span>
                    <span class="text-gold-600">Detalhes da Questão</span>
                </nav>
                <h2 class="font-classic text-3xl text-navy-900">Questão Descritiva #{{ $question->order_index }}</h2>
                <p class="text-slate-500 font-bold text-xs uppercase tracking-tighter">
                    {{ $question->classroom->name ?? 'Geral (Todas as Turmas)' }} •
                    {{ $question->subject->name ?? 'Comportamental' }}
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('questions.edit', $question->id) }}"
                    class="bg-gold-500 text-navy-950 px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar Questão
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 mb-8">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-2">Enunciado</span>
            <p class="text-navy-900 text-lg font-semibold leading-relaxed">
                {{ $question->statement }}
            </p>
        </div>

        <div class="mt-8 flex justify-center">
            <a href="{{ route('questions.index') }}"
                class="text-slate-400 hover:text-navy-900 font-bold text-xs uppercase tracking-widest transition-colors">
                ← Voltar para listagem
            </a>
        </div>
    </div>
@endsection
