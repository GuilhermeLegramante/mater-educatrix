@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- HEADER DO MÓDULO --}}
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-6">
            <div>
                <p class="text-gold-600 font-bold uppercase tracking-widest text-[10px] mb-1">
                    Biblioteca & Consulta
                </p>
                <h1 class="font-classic text-4xl text-navy-900 uppercase tracking-tight">
                    Cadastrar Novo Livro
                </h1>
            </div>

            <a href="{{ route('books.index') }}"
                class="px-6 py-3 bg-slate-100 text-navy-900 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">
                ← Voltar ao Acervo
            </a>
        </div>

        {{-- FORMULÁRIO --}}
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <form method="POST" action="{{ route('books.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Título --}}
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Título do Livro *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            placeholder="Ex: Dom Casmurro"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500 @error('title') border-rose-500 @enderror">
                        @error('title')
                            <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Autor --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Autor *</label>
                        <input type="text" name="author" value="{{ old('author') }}" required
                            placeholder="Ex: Machado de Assis"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500 @error('author') border-rose-500 @enderror">
                        @error('author')
                            <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ISBN --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn') }}" placeholder="Ex: 978-85-359-0277-7"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500">
                    </div>

                    {{-- Editora --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Editora</label>
                        <input type="text" name="publisher" value="{{ old('publisher') }}"
                            placeholder="Ex: Companhia das Letras"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500">
                    </div>

                    {{-- Cidade de Publicação --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Cidade de
                            Publicação</label>
                        <input type="text" name="publication_city" value="{{ old('publication_city') }}"
                            placeholder="Ex: Rio de Janeiro"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500">
                    </div>

                    {{-- Ano de Publicação --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Ano da Edição</label>
                        <input type="text" name="publication_year" value="{{ old('publication_year') }}"
                            placeholder="Ex: 2019"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500">
                    </div>

                    {{-- Ano da 1ª Edição --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Ano da 1ª Edição</label>
                        <input type="text" name="first_edition_year" value="{{ old('first_edition_year') }}"
                            placeholder="Ex: 1899"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500">
                    </div>

                    {{-- Tipo de Livro --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Tipo de Livro *</label>
                        <input type="text" name="type" value="{{ old('type', 'Literatura') }}" required
                            placeholder="Ex: Literatura, Espiritualidade"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500">
                    </div>

                    {{-- Disciplina --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Disciplina /
                            Categoria</label>
                        <input type="text" name="discipline" value="{{ old('discipline') }}"
                            placeholder="Ex: Português, História"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500">
                    </div>

                    {{-- Estante / Localização --}}
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Estante /
                            Localização</label>
                        <input type="text" name="location_shelf" value="{{ old('location_shelf') }}"
                            placeholder="Ex: Estante A, Prateleira 2"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500">
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Status Inicial *</label>
                        <select name="status" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
                            <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Disponível
                            </option>
                            <option value="borrowed" {{ old('status') === 'borrowed' ? 'selected' : '' }}>Emprestado
                            </option>
                        </select>
                    </div>

                </div>

                {{-- BOTÕES DE AÇÃO --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('books.index') }}"
                        class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gold-500 text-navy-900 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20">
                        Salvar Livro
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
