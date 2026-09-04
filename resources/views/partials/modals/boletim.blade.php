{{-- MODAL NATIVO: GERAR BOLETIM PDF --}}
<div id="modal-boletim" class="fixed inset-0 z-50 overflow-y-auto hidden">
    {{-- Backdrop escurecido com clique para fechar --}}
    <div class="fixed inset-0 bg-navy-950/80 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-boletim')">
    </div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div id="modal-content"
            class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-[#0f1a34] border border-amber-500/20 p-8 text-left shadow-2xl transition-all scale-95 opacity-0">

            {{-- Header do Modal --}}
            <div class="flex items-center justify-between border-b border-slate-800 pb-5 mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-serif text-xl font-bold text-amber-400">Gerar Boletim Acadêmico</h3>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-mono">Exportação
                            oficial em PDF</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-boletim')"
                    class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Formulário de Configuração do Boletim --}}
            <form
                action="{{ route('students.report-card.pdf', ['classroom' => $activeClassroom->id, 'student' => $student->id]) }}"
                method="GET" target="_blank" class="space-y-6">

                <div class="space-y-4 mb-6">
                    <span class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-1">Componentes
                        Adicionais</span>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="block text-xs font-bold text-navy-900">Rendimento
                                Escolar</span>
                            <span class="text-[10px] text-slate-400">Notas, médias e faltas das
                                disciplinas básicas.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="include_grades" value="1" checked class="sr-only peer">
                            <div
                                class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="block text-xs font-bold text-navy-900">Ocorrências
                                & Atendimentos</span>
                            <span class="text-[10px] text-slate-400">Histórico disciplinar e
                                prontuários
                                médicos.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="include_occurrences" value="1" class="sr-only peer">
                            <div
                                class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="block text-xs font-bold text-navy-900">Parecer Descritivo</span>
                            <span class="text-[10px] text-slate-400">Acompanhamento dos professores e desenvolvimento
                                pessoal.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="include_preceptory" value="1" class="sr-only peer">
                            <div
                                class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                            </div>
                        </label>
                    </div>

                    {{-- OPÇÃO: AVALIAÇÃO DESCRITIVA (MATRIZ) --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="block text-xs font-bold text-navy-900">Avaliação Descritiva (Matriz)</span>
                            <span class="text-[10px] text-slate-400">Exibir o questionário com as respostas e pareceres
                                bimestrais.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="include_descriptive_evaluation" value="1"
                                class="sr-only peer">
                            <div
                                class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Ações do Rodapé do Modal --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modal-boletim')"
                        class="px-5 py-2.5 text-xs font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-100 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="bg-gold-500 text-navy-950 px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Compilar & Imprimir
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
