@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-fade-in">

        {{-- CABEÇALHO DA PÁGINA --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-200 pb-6">
            <div>
                <p class="text-gold-600 font-bold uppercase tracking-widest text-[10px] mb-1">
                    Exportação Oficial
                </p>
                <h1 class="font-classic text-3xl md:text-4xl text-navy-900 uppercase tracking-tight">
                    Gestão e Geração de Boletins em Lote
                </h1>
                <p class="text-slate-500 text-xs font-medium mt-1">
                    Gere boletins acadêmicos em formato PDF de forma individual, por turma completa ou geral.
                </p>
            </div>
        </div>

        {{-- ALERTA DE SUCESSO (Opcional caso queira um feedback em tela) --}}
        @if (session('success'))
            <div
                class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl text-xs font-bold uppercase tracking-wider flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- FORMULÁRIO DE CONFIGURAÇÃO E FILTROS --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
            <form action="{{ route('reports.report-cards.generate-batch') }}" method="GET" class="space-y-6"
                onsubmit="showDownloadAlert()">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

                    {{-- Escopo da Geração --}}
                    <div class="space-y-2">
                        <label for="scope" class="block font-bold text-navy-900 text-[10px] uppercase tracking-widest">
                            Escopo de Geração <span class="text-rose-500">*</span>
                        </label>
                        <select name="scope" id="scope" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-navy-900 font-semibold focus:border-gold-500 focus:ring-1 focus:ring-gold-500 transition-all outline-none"
                            onchange="toggleScopeFields(this.value)">
                            <option value="classroom">Turma Inteira</option>
                            <option value="student">Aluno Individual</option>
                            <option value="all">Todos os Alunos do Sistema</option>
                        </select>
                    </div>

                    {{-- Filtro por Turma --}}
                    <div class="space-y-2" id="field-classroom">
                        <label for="classroom_id"
                            class="block font-bold text-navy-900 text-[10px] uppercase tracking-widest">
                            Turma <span class="text-rose-500">*</span>
                        </label>
                        <select name="classroom_id" id="classroom_id"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-navy-900 font-semibold focus:border-gold-500 focus:ring-1 focus:ring-gold-500 transition-all outline-none"
                            onchange="fetchStudentsForClassroom(this.value)">
                            <option value="">Selecione uma Turma</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}"
                                    {{ $selectedClassroomId == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro por Aluno (Visível apenas se escopo for aluno individual) --}}
                    <div class="space-y-2 hidden" id="field-student">
                        <label for="student_id" class="block font-bold text-navy-900 text-[10px] uppercase tracking-widest">
                            Estudante <span class="text-rose-500">*</span>
                        </label>
                        <select name="student_id" id="student_id"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-navy-900 font-semibold focus:border-gold-500 focus:ring-1 focus:ring-gold-500 transition-all outline-none">
                            <option value="">Selecione o Estudante</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- COMPONENTES ADICIONAIS (MESMO PADRÃO DA MODAL) --}}
                <div class="border-t border-slate-100 pt-6 mt-6">
                    <span class="block text-[10px] uppercase font-black tracking-widest text-slate-400 mb-4">Componentes
                        Adicionais do Boletim</span>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <div>
                                <span class="block text-xs font-bold text-navy-900">Rendimento Escolar</span>
                                <span class="text-[10px] text-slate-400">Notas, médias e faltas das disciplinas
                                    básicas.</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="include_grades" value="1" checked class="sr-only peer">
                                <div
                                    class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <div>
                                <span class="block text-xs font-bold text-navy-900">Ocorrências & Atendimentos</span>
                                <span class="text-[10px] text-slate-400">Histórico disciplinar e prontuários médicos.</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="include_occurrences" value="1" class="sr-only peer">
                                <div
                                    class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold-500">
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
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

                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
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
                </div>

                {{-- BOTÃO DE SUBMISSÃO --}}
                <div class="pt-4 flex justify-end">
                    <button type="submit" id="btn-submit"
                        class="px-8 py-4 bg-gold-500 text-navy-950 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20 flex items-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Gerar e Baixar Lote de Boletins (.zip)
                    </button>
                </div>

            </form>
        </div>

    </div>

    <script>
        function toggleScopeFields(scope) {
            const fieldClassroom = document.getElementById('field-classroom');
            const fieldStudent = document.getElementById('field-student');

            if (scope === 'student') {
                fieldClassroom.classList.remove('hidden');
                fieldStudent.classList.remove('hidden');
            } else if (scope === 'classroom') {
                fieldClassroom.classList.remove('hidden');
                fieldStudent.classList.add('hidden');
            } else {
                // 'all'
                fieldClassroom.classList.add('hidden');
                fieldStudent.classList.add('hidden');
            }
        }

        function fetchStudentsForClassroom(classroomId) {
            if (!classroomId) return;
            // Redireciona para a mesma tela de lote passando a turma para atualizar os alunos disponíveis
            const url = new URL(window.location.href);
            url.searchParams.set('classroom_id', classroomId);
            window.location.href = url.toString();
        }

        function showDownloadAlert() {
            const btn = document.getElementById('btn-submit');
            btn.innerHTML = `
            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Compactando e gerando arquivos...
        `;
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            // Retorna o botão ao normal após alguns segundos para o usuário poder gerar novos lotes se precisar
            setTimeout(() => {
                btn.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Gerar e Baixar Lote de Boletins (.zip)
            `;
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
            }, 5000);
        }
    </script>
@endsection
