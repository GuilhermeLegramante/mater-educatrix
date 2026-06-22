@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8" x-data="{ allSelected: false, classrooms: [] }">

        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-300 text-emerald-800 p-4 rounded-xl text-sm font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="border-b border-slate-100 pb-6">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Painel
                Administrativo</span>
            <h1 class="font-classic text-3xl text-navy-900 mt-1">Gerador de Calendário Letivo</h1>
            <p class="text-slate-500 text-sm mt-1">Gere automaticamente dias de aula em lote para as
                turmas, removendo finais de semana e feriados.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div
                class="lg:col-span-1 bg-white border border-slate-200 p-6 rounded-2xl shadow-sm">
                <h3 class="font-bold text-base text-navy-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Configurar Período
                </h3>

                <form action="{{ route('admin.calendar.generate') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Ano Letivo</label>
                        <select name="year"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-semibold p-2.5 text-slate-700">
                            <option value="2026" selected>2026</option>
                            <option value="2027">2027</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Mês de Referência</label>
                        <select name="month"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-semibold p-2.5 text-slate-700">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-bold text-slate-400 uppercase">Aplicar para as Turmas</label>
                            <button type="button"
                                @click="allSelected = !allSelected; classrooms = allSelected ? [ @foreach ($classrooms as $c) '{{ $c->id }}', @endforeach ] : []"
                                class="text-[10px] font-bold text-amber-600 uppercase hover:underline">
                                <span x-text="allSelected ? 'Desmarcar todas' : 'Marcar todas'"></span>
                            </button>
                        </div>

                        <div
                            class="space-y-2 max-h-48 overflow-y-auto p-2 bg-slate-50 rounded-xl border border-slate-100">
                            @foreach ($classrooms as $classroom)
                                <label
                                    class="flex items-center gap-2.5 p-1.5 cursor-pointer rounded-lg hover:bg-slate-100 transition-colors">
                                    <input type="checkbox" name="classroom_ids[]" value="{{ $classroom->id }}"
                                        x-model="classrooms"
                                        class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                    <span
                                        class="text-sm font-medium text-slate-700">{{ $classroom->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('classroom_ids')
                            <span class="text-xs text-rose-500 font-medium mt-1 block">Selecione pelo menos uma turma.</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-amber-500 text-navy-950 font-black rounded-xl shadow-sm hover:scale-[1.01] transition-transform text-sm uppercase tracking-wider">
                        Processar e Gerar Dias
                    </button>
                </form>

                <div class="my-6 border-t border-slate-100"></div>

                <div x-data="{ openResetModal: false, yearInput: '' }">
                    <button @click="openResetModal = true" type="button"
                        class="w-full py-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 font-bold rounded-xl text-xs uppercase tracking-wider transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Limpar Calendário Anual
                    </button>

                    <div x-show="openResetModal"
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm"
                        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

                        <div @click.away="openResetModal = false"
                            class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl border border-slate-200 space-y-4">

                            <div>
                                <h4
                                    class="text-base font-black text-slate-900 uppercase tracking-tight flex items-center gap-2 text-rose-600">
                                    ⚠️ Atenção: Zona de Perigo
                                </h4>
                                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                    Esta ação irá **deletar permanentemente todos os dias letivos** do ano selecionado.
                                    Faltas lançadas nestes dias também serão excluídas.
                                </p>
                            </div>

                            <form action="{{ route('admin.calendar.clearYear') }}" method="POST" class="space-y-3">
                                @csrf
                                @method('DELETE')

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Qual ano deseja
                                        apagar?</label>
                                    <select name="year_to_clear" x-model="yearInput"
                                        class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-semibold p-2">
                                        <option value="">Selecione o ano...</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Digite o mesmo
                                        ano para confirmar:</label>
                                    <input type="number" name="confirm_year" placeholder="Ex: 2026" required
                                        class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-semibold p-2 text-slate-700 focus:ring-rose-500 focus:border-rose-500">
                                </div>

                                <div class="flex gap-2 pt-2">
                                    <button @click="openResetModal = false" type="button"
                                        class="w-1/2 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl text-xs uppercase tracking-wider">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="w-1/2 py-2 bg-rose-600 text-white font-black rounded-xl text-xs uppercase tracking-wider shadow-sm hover:bg-rose-700 disabled:opacity-40 disabled:cursor-not-allowed"
                                        ::disabled="yearInput === ''">
                                        Confirmar Exclusão
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100">
                    <h3 class="font-bold text-base text-navy-900">Dias Letivos Gerados Recentemente</h3>
                    <p class="text-xs text-slate-400">Use a lixeira para apagar um dia letivo caso precise emendar feriados
                        ou cancelar aulas.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-xs font-bold text-slate-400 uppercase">
                                <th class="p-3">Data</th>
                                <th class="p-3">Dia da Semana</th>
                                <th class="p-3">Turma</th>
                                <th class="p-3 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentDays as $day)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="p-3 font-semibold text-slate-800">
                                        {{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="p-3 text-slate-500 capitalize">
                                        {{ \Carbon\Carbon::parse($day->date)->translatedFormat('l') }}
                                    </td>
                                    <td class="p-3 font-medium text-slate-700">
                                        {{ $day->classroom->name }}
                                    </td>
                                    <td class="p-3 text-center">
                                        <form action="{{ route('admin.calendar.day.destroy', $day->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja remover este dia do diário de classe?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg transition-colors"
                                                title="Excluir dia letivo">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-400 italic text-xs">Nenhum dia
                                        letivo gerado no banco de dados. Use o formulário ao lado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($recentDays->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50">
                        {{ $recentDays->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
