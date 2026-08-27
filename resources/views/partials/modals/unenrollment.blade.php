<!-- Modal de Confirmação de Desmatrícula -->
<div id="modal-unenroll" onclick="if(event.target === this) closeModal('modal-unenroll')"
    class="fixed inset-0 bg-navy-950/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-all">

    <div
        class="bg-white p-8 w-full max-w-md rounded-3xl shadow-2xl border border-slate-200 transform transition-all scale-95 relative">

        <button type="button" onclick="closeModal('modal-unenroll')"
            class="absolute top-6 right-6 p-2 text-slate-400 hover:text-slate-600 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div
            class="w-12 h-12 rounded-2xl bg-rose-100 border border-rose-200 flex items-center justify-center text-rose-600 mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <h3 class="font-classic text-2xl text-navy-900 mb-1">
            Desmatricular Aluno
        </h3>

        <p class="text-slate-500 text-xs mb-6">
            Tem certeza que deseja remover a matrícula de <strong id="unenroll-student-name"
                class="text-navy-900 font-bold"></strong> desta turma?
        </p>

        <form id="form-unenroll" method="POST" action="" class="space-y-4">
            @csrf
            @method('DELETE')

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-unenroll')"
                    class="w-full px-5 py-3 rounded-xl border border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500 hover:bg-slate-50 transition-all">
                    Cancelar
                </button>
                <button type="submit"
                    class="w-full bg-rose-600 text-white font-black py-3 rounded-xl uppercase tracking-widest text-xs hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/20">
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para controlar a abertura dinâmica do Modal -->
<script>
    function confirmUnenroll(actionUrl, studentName) {
        // Atualiza a action do formulário com a rota do aluno selecionado
        document.getElementById('form-unenroll').action = actionUrl;

        // Atualiza o nome do aluno na mensagem do modal
        document.getElementById('unenroll-student-name').innerText = studentName;

        // Exibe o modal usando a função global do projeto
        openModal('modal-unenroll');
    }
</script>
