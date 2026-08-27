@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto animate-fade-in" x-data="{ openCreateModal: false }">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="font-classic text-4xl text-navy-900">Tipos de Ocorrência</h1>
                <p class="text-slate-500 text-sm">Gerencie os moldes e categorias para os registros de atendimento e
                    indisciplina.</p>
            </div>

            <button @click="openCreateModal = true"
                class="bg-gold-500 text-navy-950 px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Novo Tipo
            </button>
        </div>

        <div
            class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
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
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-{{ $type->color }}-50 $type->color }}-950/40 text-{{ $type->color }}-600 $type->color }}-400 border border-{{ $type->color }}-200 $type->color }}-800/60">
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
                                    <div class="flex items-center justify-end gap-3">

                                        <form action="{{ route('occurrence-types.toggle', $type->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-slate-400 hover:text-navy-900 transition-colors p-1"
                                                title="{{ $type->is_active ? 'Desativar Categoria' : 'Ativar Categoria' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </button>
                                        </form>

                                        <form action="{{ route('occurrence-types.destroy', $type->id) }}" method="POST"
                                            class="form-delete inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 hover:bg-red-50 rounded-xl text-red-400 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>

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

        <div x-show="openCreateModal" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm"
                    @click="openCreateModal = false">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block width-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white border border-slate-200 shadow-2xl rounded-3xl sm:w-full">

                    <div
                        class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                        <div>
                            <h3
                                class="font-classic text-lg font-bold text-navy-900 uppercase tracking-wider">
                                Novo Tipo de Ocorrência
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Defina uma nova categoria de registro para os alunos.
                            </p>
                        </div>
                        <button @click="openCreateModal = false"
                            class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('occurrence-types.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">
                                Nome da Categoria
                            </label>
                            <input type="text" name="name" required
                                placeholder="Ex: Atendimento na Enfermaria, Indisciplina..."
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-transparent text-sm text-navy-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                            @error('name')
                                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">
                                Cor Identificadora (Badge)
                            </label>
                            <div
                                class="grid grid-cols-6 gap-3 p-3 bg-slate-50 border border-slate-100 rounded-2xl">
                                @foreach (['emerald' => 'bg-emerald-500', 'amber' => 'bg-amber-500', 'orange' => 'bg-orange-500', 'rose' => 'bg-rose-500', 'violet' => 'bg-violet-500', 'slate' => 'bg-slate-500'] as $key => $colorClass)
                                    <label
                                        class="relative flex items-center justify-center cursor-pointer p-0.5 rounded-full border-2 border-transparent has-[:checked]:border-navy-900 transition-all">
                                        <input type="radio" name="color" value="{{ $key }}" class="sr-only"
                                            {{ $loop->first ? 'checked' : '' }}>
                                        <span class="w-6 h-6 rounded-full {{ $colorClass }} shadow-sm block"></span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-slate-400 mt-2">A cor ajuda os gestores na triagem visual rápida da
                                gravidade na ficha do aluno.</p>
                        </div>

                        <div
                            class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="openCreateModal = false"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-100 transition-colors">
                                Cancelar
                            </button>

                            <button type="submit"
                                class="bg-gold-500 text-navy-950 px-6 py-2.5 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center justify-center">
                                Salvar Categoria
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
