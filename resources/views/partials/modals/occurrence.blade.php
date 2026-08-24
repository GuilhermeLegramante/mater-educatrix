<div id="modal-ocorrencia" class="fixed inset-0 z-50 overflow-y-auto hidden">
    {{-- Backdrop escurecido --}}
    <div class="fixed inset-0 bg-navy-950/80 backdrop-blur-sm transition-opacity"
        onclick="closeModal('modal-ocorrencia')"></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div id="modal-content"
            class="relative w-full max-w-xl transform overflow-hidden rounded-3xl bg-[#0f1a34] border border-amber-500/20 p-8 text-left shadow-2xl transition-all scale-95 opacity-0">

            {{-- Header do Modal --}}
            <div class="flex items-center justify-between border-b border-slate-800 pb-5 mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-serif text-xl font-bold text-amber-400">Registrar Ocorrência</h3>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-mono">Prontuário &
                            Atendimento</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-ocorrencia')"
                    class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Formulário de Cadastro de Ocorrência --}}
            <form action="{{ route('students.occurrences.store', $student->id) }}" method="POST" class="space-y-5">
                @csrf

                {{-- Exibição de erros de validação --}}
                @if ($errors->any())
                    <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl">
                        <p class="text-xs font-bold text-rose-400 mb-1">Não foi possível salvar a ocorrência:</p>
                        <ul class="list-disc list-inside text-xs text-rose-300 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <input type="hidden" name="student_id" value="{{ $student->id }}">
                @if (isset($activeClassroom))
                    <input type="hidden" name="classroom_id" value="{{ $activeClassroom->id }}">
                @endif

                {{-- Tipo e Data --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-300 mb-2">
                            Tipo de Ocorrência
                        </label>
                        <select name="occurrence_type_id" required
                            class="w-full bg-[#0b1329] border border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-amber-500">
                            <option value="" disabled {{ old('occurrence_type_id') ? '' : 'selected' }}>Selecione
                                um tipo...</option>
                            @if (isset($occurrenceTypes))
                                @foreach ($occurrenceTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('occurrence_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-300 mb-2">
                            Data e Horário
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                class="w-full bg-[#0b1329] border border-slate-800 rounded-xl px-3 py-3 text-xs text-slate-200 focus:outline-none focus:border-amber-500">
                            <input type="time" name="time" value="{{ old('time', date('H:i')) }}"
                                class="w-full bg-[#0b1329] border border-slate-800 rounded-xl px-3 py-3 text-xs text-slate-200 focus:outline-none focus:border-amber-500">
                        </div>
                    </div>
                </div>

                {{-- Descrição do Fato --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-300 mb-2">
                        Descrição do Fato / Atendimento
                    </label>
                    <textarea name="description" rows="3" required placeholder="Descreva detalhadamente o ocorrido..."
                        class="w-full bg-[#0b1329] border border-slate-800 rounded-xl p-3 text-xs text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500">{{ old('description') }}</textarea>
                </div>

                {{-- Ações Tomadas / Providências --}}
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-300 mb-2">
                        Providências Adotadas (Opcional)
                    </label>
                    <textarea name="actions_taken" rows="2"
                        placeholder="Ex: Contato com responsáveis, advertência verbal, encaminhamento pedagógico..."
                        class="w-full bg-[#0b1329] border border-slate-800 rounded-xl p-3 text-xs text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500">{{ old('actions_taken') }}</textarea>
                </div>

                {{-- Rodapé do Formulário --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" onclick="closeModal('modal-ocorrencia')"
                        class="px-5 py-2.5 text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-white rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all">
                        Salvar Ocorrência
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
