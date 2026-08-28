@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- HEADER DO MÓDULO --}}
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-6">
            <div>
                <p class="text-gold-600 font-bold uppercase tracking-widest text-[10px] mb-1">
                    Ficha Catalográfica
                </p>
                <h1 class="font-classic text-4xl text-navy-900 uppercase tracking-tight">
                    Detalhes do Livro
                </h1>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('books.index') }}"
                    class="px-6 py-3 bg-slate-100 text-navy-900 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">
                    ← Voltar
                </a>
                <a href="{{ route('books.edit', $book) }}"
                    class="px-6 py-3 bg-gold-500 text-navy-900 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20">
                    Editar Registro
                </a>
                <a href="{{ route('books.pdf-card', $book) }}" target="_blank"
                    class="px-6 py-3 bg-navy-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-navy-800 transition-all shadow-lg">
                    🖨️ Imprimir Ficha PDF
                </a>
            </div>
        </div>

        {{-- CARD DE DETALHES --}}
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-8">

            {{-- TÍTULO E STATUS --}}
            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 border-b border-slate-100 pb-6">
                <div>
                    <span
                        class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-navy-900/5 text-navy-900 inline-block mb-2">
                        {{ $book->type }}
                    </span>
                    <h2 class="text-3xl font-black text-navy-900 tracking-tight">{{ $book->title }}</h2>
                    <p class="text-slate-500 font-bold text-base mt-1">{{ $book->author }}</p>
                </div>
                <div>
                    @if ($book->status === 'available')
                        <span
                            class="px-4 py-2 rounded-full text-xs font-black uppercase bg-emerald-100 text-emerald-700 inline-block">
                            Disponível
                        </span>
                    @else
                        <span
                            class="px-4 py-2 rounded-full text-xs font-black uppercase bg-amber-100 text-amber-700 inline-block">
                            Emprestado
                        </span>
                    @endif
                </div>
            </div>

            {{-- INFORMAÇÕES CATALOGRÁFICAS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <span class="block text-[10px] font-black uppercase text-slate-400 mb-1">ISBN</span>
                    <span class="text-sm font-bold text-navy-900 font-mono">{{ $book->isbn ?? 'Não informado' }}</span>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <span class="block text-[10px] font-black uppercase text-slate-400 mb-1">Editora</span>
                    <span class="text-sm font-bold text-navy-900">{{ $book->publisher ?? 'Não informada' }}</span>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <span class="block text-[10px] font-black uppercase text-slate-400 mb-1">Cidade / Anos</span>
                    <span class="text-sm font-bold text-navy-900">
                        {{ $book->publication_city ?? '-' }} (Edição: {{ $book->publication_year ?? '-' }} | 1ª Ed:
                        {{ $book->first_edition_year ?? '-' }})
                    </span>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <span class="block text-[10px] font-black uppercase text-slate-400 mb-1">Disciplina / Categoria</span>
                    <span class="text-sm font-bold text-gold-600 uppercase">{{ $book->discipline ?? 'Geral' }}</span>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 sm:col-span-2">
                    <span class="block text-[10px] font-black uppercase text-slate-400 mb-1">Estante / Localização
                        Físicas</span>
                    <span class="text-sm font-bold text-navy-900">{{ $book->location_shelf ?? 'Não especificada' }}</span>
                </div>

            </div>

        </div>
    </div>
@endsection
