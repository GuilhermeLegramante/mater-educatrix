<!DOCTYPE html>
<html lang="pt-br" x-data="{
    sidebarOpen: window.innerWidth > 768 ? (localStorage.getItem('sidebar') === 'true') : false,
    darkMode: localStorage.getItem('dark') === 'true'
}" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('dark', val));
$watch('sidebarOpen', val => localStorage.setItem('sidebar', val))">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mater Educatrix</title>

    <link rel="icon" href="/img/icone.png">

    <link rel="manifest" href="{{ asset('manifest.json') }}" crossorigin="use-credentials">

    <meta name="theme-color" content="#2b2c43">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/img/icone.png">

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Inter:wght@400;600;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Função Global para abrir qualquer modal
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                // Força um pequeno delay para a animação de opacidade funcionar
                setTimeout(() => {
                    const content = modal.querySelector('.modal-content');
                    if (content) {
                        content.classList.remove('scale-95', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }
                }, 10);
            } else {
                console.error('Modal com ID ' + id + ' não encontrado.');
            }
        }

        // Função Global para fechar qualquer modal
        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                const content = modal.querySelector('.modal-content');
                if (content) {
                    content.classList.remove('scale-100', 'opacity-100');
                    content.classList.add('scale-95', 'opacity-0');
                }
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 200);
            }
        }

        // Fechar modais ao clicar fora (no fundo escuro)
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-backdrop')) {
                event.target.classList.add('hidden');
            }
        }
    </script>


    <!-- Tailwind Play CDN e Configuração Corrigida -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617'
                        },
                        gold: {
                            400: '#fbbf24',
                            500: '#d4af37',
                            600: '#92400e'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .font-classic {
            font-family: 'Cinzel', serif;
        }

        body {
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-navy-950 text-slate-900 dark:text-slate-100 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden relative">

        <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-navy-950/40 backdrop-blur-md md:hidden"
            @click="sidebarOpen = false" x-cloak></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 w-72 bg-navy-900 text-white transition-transform duration-300 transform md:relative md:translate-x-0 border-r border-gold-500/20 shadow-2xl"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:w-20 md:translate-x-0'" x-cloak>
            @include('layouts.partials.sidebar')
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.partials.navbar')

            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                <!-- BOTÕES DE NAVEGAÇÃO GLOBAIS (APARECERÃO EM TODAS AS PÁGINAS) -->
                <div class="max-w-7xl mx-auto flex items-center gap-2 mb-6">
                    <!-- Botão Voltar -->
                    <button onclick="window.history.back()"
                        class="flex items-center gap-1 bg-white dark:bg-navy-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 px-3 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-sm"
                        title="Voltar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="hidden sm:inline">Voltar</span>
                    </button>

                    <!-- Botão Avançar -->
                    <button onclick="window.history.forward()"
                        class="flex items-center gap-1 bg-white dark:bg-navy-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 px-3 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-sm"
                        title="Avançar">
                        <span class="hidden sm:inline">Avançar</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Botão Home (Página Inicial / Painel de Turmas) -->
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-1 bg-navy-900 hover:bg-gold-600 text-white px-3 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-sm"
                        title="Página Inicial">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="hidden sm:inline">Home</span>
                    </a>
                </div>

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Intercepta o clique em botões de formulários com a classe 'form-delete'
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('form-delete')) {
                e.preventDefault(); // Para o envio automático

                const form = e.target;

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Esta ação é irreversível e pode afetar registros vinculados!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0f172a', // Navy-900
                    cancelButtonColor: '#94a3b8', // Slate-400
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar',
                    background: '#ffffff',
                    borderRadius: '1.5rem',
                    customClass: {
                        title: 'font-classic text-navy-900',
                        confirmButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-6 py-3',
                        cancelButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-6 py-3'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Envia o formulário se o usuário confirmar
                    }
                });
            }
        });
    </script>

    @if (session('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                background: '#0f172a',
                color: '#ffffff',
                iconColor: '#eab308' // Gold-500
            });
        </script>
    @endif

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registrado com sucesso!', reg.scope))
                    .catch(err => console.log('Falha ao registrar o Service Worker:', err));
            });
        }
    </script>
</body>

</html>
