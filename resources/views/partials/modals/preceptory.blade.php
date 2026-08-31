<div id="modal-preceptoria" onclick="if(event.target === this) closeModal('modal-preceptoria')"
    class="fixed inset-0 bg-navy-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-4 modal-backdrop transition-all">

    <div id="modal-content"
        class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-transparent overflow-hidden modal-content transform transition-all scale-95 opacity-0">

        <div
            class="p-6 bg-navy-900 text-white flex justify-between items-center border-b border-white/[0.05]">
            <h3 class="font-classic text-xl font-black uppercase tracking-wide text-gold-500">Novo Parecer Descritivo</h3>
            </h3>
            <button onclick="closeModal('modal-preceptoria')"
                class="text-white/50 hover:text-white text-2xl transition-colors font-bold">&times;</button>
        </div>

        <form action="{{ route('preceptory.store', $activeClassroom) }}" method="POST" class="p-8 space-y-5">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label
                        class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider">Período</label>
                    <select name="bimester"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold text-navy-900 outline-none focus:border-gold-500 transition-colors">
                        {{-- Setar o option o o bimestre ativo nas configurações da Escola --}}

                        <option value="1" class="dark:bg-navy-950" {{ $bimester == 1 ? 'selected' : '' }}>1º
                            Bimestre</option>
                        <option value="2" class="dark:bg-navy-950" {{ $bimester == 2 ? 'selected' : '' }}>2º
                            Bimestre</option>
                        <option value="3" class="dark:bg-navy-950" {{ $bimester == 3 ? 'selected' : '' }}>3º
                            Bimestre</option>
                        <option value="4" class="dark:bg-navy-950" {{ $bimester == 4 ? 'selected' : '' }}>4º
                            Bimestre</option>
                    </select>
                </div>

                <div>
                    <label
                        class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider">Escopo
                        / Disciplina</label>
                    <select name="subject_id"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold text-navy-900 outline-none focus:border-gold-500 transition-colors">
                        <option value="" class="dark:bg-navy-950">Desenvolvimento Geral</option>
                        @foreach ($activeClassroom->subjects as $subject)
                            <option value="{{ $subject->id }}" class="dark:bg-navy-950"
                                {{ $subjectId == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label
                    class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider">Relato
                    Observacional</label>
                <textarea name="content" rows="5" required
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-serif italic text-lg text-navy-900 placeholder-slate-400 outline-none focus:border-gold-500 transition-colors resize-none"
                    placeholder="Descreva as atitudes, progresso do aluno e oportunidades de melhoria..."></textarea>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-gold-500 text-navy-900 font-black py-4 rounded-xl uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20">
                    Gravar Parecer
                </button>
                <button type="button" onclick="closeModal('modal-preceptoria')"
                    class="w-full mt-3 text-slate-400 text-[10px] font-bold uppercase tracking-widest hover:text-navy-900 transition-colors">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>
