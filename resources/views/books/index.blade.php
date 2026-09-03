@extends('layouts.app')

@section('content')
    <div x-data="{ showDeleteModal: false, deleteUrl: '', bookTitle: '' }">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- HEADER DO MÓDULO --}}
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-6">
                <div>
                    <p class="text-gold-600 font-bold uppercase tracking-widest text-[10px] mb-1">
                        Biblioteca & Consulta
                    </p>
                    <h1 class="font-classic text-4xl text-navy-900 uppercase tracking-tight">
                        Acervo de Livros
                    </h1>
                </div>

                <a href="{{ route('books.create') }}"
                    class="px-6 py-3 bg-gold-500 text-navy-900 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20">
                    + Cadastrar Novo Livro
                </a>
            </div>

            {{-- BARRA DE FILTROS E PESQUISA --}}
            <form method="GET" action="{{ route('books.index') }}"
                class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm grid grid-cols-1 md:grid-cols-5 gap-4">

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">
                        Buscar por Título, Autor, Editora ou ISBN
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Ex: Tom Sawyer, Olavo Bilac..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-navy-900 outline-none focus:border-gold-500">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Tipo de Livro</label>
                    <select name="type" onchange="this.form.submit()"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
                        <option value="">Todos os Tipos</option>
                        @foreach ($types as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Disciplina</label>
                    <select name="discipline" onchange="this.form.submit()"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
                        <option value="">Todas as Disciplinas</option>
                        @foreach ($disciplines as $d)
                            <option value="{{ $d }}" {{ request('discipline') == $d ? 'selected' : '' }}>
                                {{ $d }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- NOVO FILTRO: FICHA IMPRESSA --}}
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Ficha Impressa</label>
                    <select name="is_printed" onchange="this.form.submit()"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
                        <option value="">Todas</option>
                        <option value="true" {{ request('is_printed') === 'true' ? 'selected' : '' }}>Sim (Impressas)
                        </option>
                        <option value="false" {{ request('is_printed') === 'false' ? 'selected' : '' }}>Não (Pendentes)
                        </option>
                    </select>
                </div>
            </form>

            {{-- TABELA DE LIVROS --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-400 text-[9px] uppercase font-black tracking-wider">
                            <tr>
                                <th class="px-6 py-4 text-center">Ficha Impressa?</th>
                                <th class="px-8 py-4 text-center">Ações</th>
                                <th class="px-8 py-4 w-1/3 min-w-[280px]">Título & Autor</th>
                                <th class="px-6 py-4">Editora / Cidade</th>
                                <th class="px-6 py-4">Tipo & Disciplina</th>
                                <th class="px-6 py-4">Estante / Localização</th>
                                <th class="px-6 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($books as $book)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    {{-- COLUNA DE STATUS DE IMPRESSÃO --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form action="{{ route('books.toggle-printed', $book) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            @if ($book->is_printed)
                                                <button type="submit"
                                                    title="Clique para marcar como NÃO impressa a Ficha Catalográfica"
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold hover:bg-emerald-200 transition-all cursor-pointer">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                    Sim
                                                </button>
                                            @else
                                                <button type="submit"
                                                    title="Clique para marcar como impressa a Ficha Catalográfica"
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold hover:bg-amber-200 transition-all cursor-pointer">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                                    Não
                                                </button>
                                            @endif
                                        </form>
                                    </td>

                                    <td class="px-8 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- BOTÃO VER DETALHES --}}
                                            <a href="{{ route('books.show', $book) }}" title="Ver Detalhes do Livro"
                                                class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-navy-900 rounded-xl font-bold text-[10px] uppercase hover:bg-gold-500 hover:text-navy-950 transition-all whitespace-nowrap shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                    <path
                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                </svg>
                                            </a>

                                            {{-- BOTÃO IMPRIMIR FICHA PDF --}}
                                            <a href="{{ route('books.pdf-card', $book) }}" target="_blank"
                                                title="Imprimir Ficha Catalográfica"
                                                class="inline-flex items-center px-3 py-1.5 bg-navy-900 text-white rounded-xl font-bold text-[10px] uppercase hover:bg-gold-500 hover:text-navy-950 transition-all whitespace-nowrap shrink-0">
                                                🖨️
                                            </a>

                                            {{-- BOTÃO EXCLUIR (ESTILO ÍCONE SVG) --}}
                                            <button type="button"
                                                @click="deleteUrl = '{{ route('books.destroy', $book) }}'; bookTitle = '{{ addslashes($book->title) }}'; showDeleteModal = true"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-xl transition-all border border-rose-200 font-bold text-[10px] uppercase tracking-widest shrink-0 cursor-pointer"
                                                title="Excluir Livro">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="px-8 py-4">
                                        <span
                                            class="font-bold text-navy-900 text-sm block group-hover:text-gold-600 transition-colors">
                                            {{ $book->title }}
                                        </span>
                                        <span class="text-xs text-slate-500 font-medium">
                                            {{ $book->author }}
                                        </span>
                                        @if ($book->isbn)
                                            <span class="block text-[10px] text-slate-400 font-mono mt-0.5">ISBN:
                                                {{ $book->isbn }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-600">
                                        <span class="font-bold text-navy-900 block">{{ $book->publisher ?? 'N/I' }}</span>
                                        <span class="text-slate-400">{{ $book->publication_city }}
                                            ({{ $book->publication_year ?? '-' }})
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-navy-900/5 text-navy-900 block w-max">
                                            {{ $book->type }}
                                        </span>
                                        @if ($book->discipline)
                                            <span
                                                class="text-[10px] text-gold-600 font-bold uppercase tracking-wider mt-1 block">
                                                {{ $book->discipline }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate"
                                        title="{{ $book->location_shelf }}">
                                        {{ $book->location_shelf ?? 'Sem estante' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($book->status === 'available')
                                            <span
                                                class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-emerald-100 text-emerald-700">Disponível</span>
                                        @else
                                            <span
                                                class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-amber-100 text-amber-700">Emprestado</span>
                                        @endif
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 italic">
                                        Nenhum livro encontrado no acervo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 2. MODAL DE CONFIRMAÇÃO COM NOME DINÂMICO DO LIVRO --}}
                <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">

                    {{-- Fundo escurecido com Blur --}}
                    <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" @click="showDeleteModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">

                            <div class="bg-white px-6 pt-6 pb-4">
                                <div class="sm:flex sm:items-start">
                                    {{-- Ícone de Alerta em Destaque --}}
                                    <div
                                        class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                    </div>

                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-base font-bold text-slate-900" id="modal-title">
                                            Confirmar Exclusão
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-slate-500">
                                                Tem certeza que deseja excluir o livro <strong class="text-slate-800"
                                                    x-text="bookTitle"></strong>? Esta ação não pode ser desfeita.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                                <button type="button" @click="showDeleteModal = false"
                                    class="inline-flex justify-center rounded-xl bg-white px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all cursor-pointer">
                                    Cancelar
                                </button>

                                <form :action="deleteUrl" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex justify-center rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold uppercase tracking-wider text-white shadow-sm hover:bg-rose-700 transition-all cursor-pointer">
                                        Sim, Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
