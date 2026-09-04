@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto animate-fade-in" x-data="{
        openCreateModal: false,
        openEditModal: false,
        openDeleteModal: false,
        editData: { id: null, name: '', color: 'emerald', actionUrl: '' },
        deleteData: { name: '', actionUrl: '' },
        openEdit(type, actionUrl) {
            this.editData = { ...type, actionUrl: actionUrl };
            this.openEditModal = true;
        },
        openDelete(name, actionUrl) {
            this.deleteData = { name: name, actionUrl: actionUrl };
            this.openDeleteModal = true;
        }
    }">

        {{-- CABEÇALHO DO MÓDULO --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="font-classic text-4xl text-navy-900">Tipos de Ocorrência</h1>
                <p class="text-slate-500 text-sm">Gerencie os moldes e categorias para os registros de atendimento e
                    indisciplina.</p>
            </div>

            <button @click="openCreateModal = true"
                class="bg-gold-500 text-navy-950 px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center justify-center cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Novo Tipo
            </button>
        </div>

        {{-- TABELA DE TIPOS DE OCORRÊNCIA --}}
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">ID</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Nome da
                                Categoria</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">
                                Visualização / Badge</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">
                                Status</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($types as $type)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-mono font-bold text-slate-400">
                                    #{{ str_pad($type->id, 3, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap font-bold text-navy-900">
                                    {{ $type->name }}
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-{{ $type->color }}-50 text-{{ $type->color }}-600 border border-{{ $type->color }}-200">
                                        {{ $type->name }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if ($type->is_active)
                                        <span
                                            class="bg-gold-500/10 text-gold-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-gold-500/20">
                                            Ativo
                                        </span>
                                    @else
                                        <span
                                            class="bg-slate-100 text-slate-400 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-200">
                                            Inativo
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">

                                        {{-- ALTERAR STATUS (ATIVAR/DESATIVAR) --}}
                                        <form action="{{ route('occurrence-types.toggle', $type->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-slate-400 hover:text-navy-900 transition-colors p-1 cursor-pointer"
                                                title="{{ $type->is_active ? 'Desativar Categoria' : 'Ativar Categoria' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </button>
                                        </form>

                                        {{-- BOTÃO EDITAR --}}
                                        <button type="button"
                                            @click="openEdit({{ json_encode($type) }}, '{{ route('occurrence-types.update', $type->id) }}')"
                                            class="p-1 text-slate-400 hover:text-amber-500 transition-colors cursor-pointer"
                                            title="Editar Categoria">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        {{-- BOTÃO EXCLUIR --}}
                                        <button type="button"
                                            @click="openDelete('{{ addslashes($type->name) }}', '{{ route('occurrence-types.destroy', $type->id) }}')"
                                            class="p-1 text-slate-400 hover:text-rose-500 transition-colors cursor-pointer"
                                            title="Excluir Categoria">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($types->isEmpty())
            <div class="p-8 text-center text-slate-400 italic text-sm">
                Nenhum tipo de ocorrência configurado no sistema.
            </div>
        @endif

        {{-- 1. MODAL CRIAR CATEGORIA --}}
        <div x-show="openCreateModal" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm"
                    @click="openCreateModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block width-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white border border-slate-200 shadow-2xl rounded-3xl sm:w-full">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                        <div>
                            <h3 class="font-classic text-lg font-bold text-navy-900 uppercase tracking-wider">Novo Tipo de
                                Ocorrência</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Defina uma nova categoria de registro para os alunos.
                            </p>
                        </div>
                        <button @click="openCreateModal = false"
                            class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('occurrence-types.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">Nome
                                da Categoria</label>
                            <input type="text" name="name" required
                                placeholder="Ex: Atendimento na Enfermaria, Indisciplina..."
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-transparent text-sm text-navy-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>

                        {{-- SELETOR DE COR PERSONALIZADA (COLOR PICKER) --}}
                        <div x-data="{ selectedColor: '{{ old('color', $type->color ?? '#3b82f6') }}' }">
                            <label class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">
                                Cor Identificadora (Badge)
                            </label>

                            <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-2xl">
                                {{-- Input nativo do seletor de cores --}}
                                <div
                                    class="relative w-10 h-10 rounded-xl overflow-hidden border border-slate-200 shrink-0 cursor-pointer shadow-sm">
                                    <input type="color" name="color" x-model="selectedColor"
                                        class="absolute -top-2 -left-2 w-14 h-14 cursor-pointer border-0 p-0 bg-transparent">
                                </div>

                                {{-- Input de texto para código Hexadecimal --}}
                                <div class="relative w-full">
                                    <input type="text" x-model="selectedColor" placeholder="#000000" maxlength="7"
                                        class="w-full px-3 py-2 text-xs font-mono font-bold text-navy-900 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 uppercase transition-all">
                                </div>

                                {{-- Badge de Pré-visualização --}}
                                <div class="shrink-0">
                                    <span
                                        class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider text-white shadow-sm transition-all"
                                        :style="`background-color: ${selectedColor}`">
                                        Preview
                                    </span>
                                </div>
                            </div>

                            <p class="text-[10px] text-slate-400 mt-1.5">
                                Clique no quadrado colorido para abrir o seletor ou digite o código Hexadecimal.
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="openCreateModal = false"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-100 transition-colors cursor-pointer">Cancelar</button>
                            <button type="submit"
                                class="bg-gold-500 text-navy-950 px-6 py-2.5 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center justify-center cursor-pointer">Salvar
                                Categoria</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 2. MODAL EDITAR CATEGORIA --}}
        <div x-show="openEditModal" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm"
                    @click="openEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block width-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white border border-slate-200 shadow-2xl rounded-3xl sm:w-full">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                        <div>
                            <h3 class="font-classic text-lg font-bold text-navy-900 uppercase tracking-wider">Editar
                                Categoria</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Altere os dados da categoria de ocorrência.</p>
                        </div>
                        <button @click="openEditModal = false"
                            class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form :action="editData.actionUrl" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">Nome
                                da Categoria</label>
                            <input type="text" name="name" x-model="editData.name" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-transparent text-sm text-navy-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">Cor
                                Identificadora (Badge)</label>
                            <div class="grid grid-cols-6 gap-3 p-3 bg-slate-50 border border-slate-100 rounded-2xl">
                                @foreach (['emerald' => 'bg-emerald-500', 'amber' => 'bg-amber-500', 'orange' => 'bg-orange-500', 'rose' => 'bg-rose-500', 'violet' => 'bg-violet-500', 'slate' => 'bg-slate-500'] as $key => $colorClass)
                                    <label
                                        class="relative flex items-center justify-center cursor-pointer p-0.5 rounded-full border-2 border-transparent has-[:checked]:border-navy-900 transition-all">
                                        <input type="radio" name="color" value="{{ $key }}"
                                            x-model="editData.color" class="sr-only">
                                        <span class="w-6 h-6 rounded-full {{ $colorClass }} shadow-sm block"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="openEditModal = false"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-100 transition-colors cursor-pointer">Cancelar</button>
                            <button type="submit"
                                class="bg-gold-500 text-navy-950 px-6 py-2.5 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center justify-center cursor-pointer">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 3. MODAL CONFIRMAÇÃO DE EXCLUSÃO --}}
        <div x-show="openDeleteModal" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm"
                    @click="openDeleteModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block width-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white border border-slate-200 shadow-2xl rounded-3xl sm:w-full">

                    <div class="flex items-center gap-4 pb-4 mb-4 border-b border-slate-100">
                        <div
                            class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-classic text-lg font-bold text-navy-900 uppercase tracking-wider">Confirmar
                                Exclusão</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Esta ação é irreversível.</p>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 mb-6">
                        Tem certeza que deseja excluir a categoria <strong class="text-navy-900"
                            x-text="deleteData.name"></strong>?
                    </p>

                    <form :action="deleteData.actionUrl" method="POST"
                        class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        @csrf
                        @method('DELETE')

                        <button type="button" @click="openDeleteModal = false"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-100 transition-colors cursor-pointer">
                            Cancelar
                        </button>

                        <button type="submit"
                            class="bg-rose-600 text-white px-6 py-2.5 rounded-xl font-black uppercase text-xs tracking-widest hover:bg-rose-700 transition-colors shadow-lg shadow-rose-600/20 flex items-center justify-center cursor-pointer">
                            Sim, Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
