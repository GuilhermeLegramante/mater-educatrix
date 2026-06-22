<div x-show="openOccurrenceModal" class="fixed inset-0 z-50 overflow-y-auto"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm" @click="openOccurrenceModal = false"></div>

        <div
            class="inline-block w-full max-w-lg p-7 my-8 overflow-hidden text-left align-middle transition-all transform bg-white border border-slate-200 shadow-2xl rounded-3xl relative z-10">

            <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                <div>
                    <h4
                        class="font-classic text-base font-bold text-navy-900 uppercase tracking-wider">
                        Lançar Ocorrência / Atendimento
                    </h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Estudante:
                        <span
                            class="font-bold text-navy-950 px-2 py-0.5 bg-slate-100 rounded-md ml-1">
                            {{ $student->name }}
                        </span>
                    </p>
                </div>
                <button @click="openOccurrenceModal = false"
                    class="text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('students.occurrences.store', $student->id) }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">Tipo de
                        Ocorrência</label>
                    <div class="relative group">
                        <div
                            class="relative rounded-xl border border-slate-200 bg-slate-50/50 text-navy-900 focus-within:ring-2 focus-within:ring-gold-500/20 focus-within:border-gold-500 focus-within:bg-white transition-all duration-200">

                            <select id="occurrence-type-select" name="occurrence_type_id" required
                                class="w-full pl-4 pr-12 py-3 bg-transparent text-sm appearance-none outline-none border-none focus:ring-0 cursor-pointer font-medium text-navy-900">

                                <option value="" disabled selected class="text-slate-400">
                                    Selecione uma categoria...</option>

                                @foreach (\App\Models\OccurrenceType::where('is_active', true)->orderBy('name')->get() as $type)
                                    <option value="{{ $type->id }}"
                                        class="text-navy-900 py-2">
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400 group-focus-within:text-gold-500 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">Data</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-transparent text-sm text-navy-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">Horário
                            <span class="text-slate-400/60 lowercase font-normal">(opcional)</span></label>
                        <input type="time" name="time"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-transparent text-sm text-navy-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">Descrição
                        dos Fatos</label>
                    <textarea name="description" rows="4" required placeholder="Relate o ocorrido com clareza e precisão histórica..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-transparent text-sm text-navy-900 placeholder-slate-400/70 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all resize-none"></textarea>
                </div>

                <div>
                    <label
                        class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-2">Providências
                        de Imediato <span class="text-slate-400/60 lowercase font-normal">(opcional)</span></label>
                    <textarea name="actions_taken" rows="2"
                        placeholder="Ex: Responsáveis notificados por telefone, encaminhado à enfermaria..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-transparent text-sm text-navy-900 placeholder-slate-400/70 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openOccurrenceModal = false"
                        class="px-5 py-2.5 text-xs font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-100 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="bg-gold-500 text-navy-950 px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center justify-center">
                        Gravar Ocorrência
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
