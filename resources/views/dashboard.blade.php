<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mater Educatrix - Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Serif para o clássico, Sans para o moderno -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;600&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-classic {
            font-family: 'Cinzel', serif;
        }

        .bg-navy {
            background-color: #0f172a;
        }

        .text-gold {
            color: #c5a059;
        }

        .border-gold {
            border-color: #c5a059;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- SIDEBAR -->
        <aside class="w-full md:w-64 bg-navy text-white flex-shrink-0">
            <div class="p-6">
                <h1 class="font-classic text-xl text-gold font-bold tracking-widest">MATER<br>EDUCATRIX</h1>
                <p class="text-xs uppercase tracking-tighter opacity-60">Educação Clássica</p>
            </div>

            <nav class="mt-6">
                <a href="#" class="flex items-center px-6 py-3 bg-slate-800 border-r-4 border-gold text-gold">
                    <span class="mr-3">📊</span> Dashboard
                </a>
                <a href="#" class="flex items-center px-6 py-3 hover:bg-slate-800 transition duration-200">
                    <span class="mr-3">📚</span> Disciplinas
                </a>
                <a href="#" class="flex items-center px-6 py-3 hover:bg-slate-800 transition duration-200">
                    <span class="mr-3">🎓</span> Alunos
                </a>
                <a href="#" class="flex items-center px-6 py-3 hover:bg-slate-800 transition duration-200">
                    <span class="mr-3">✍️</span> Avaliações
                </a>
                <a href="#" class="flex items-center px-6 py-3 hover:bg-slate-800 transition duration-200">
                    <span class="mr-3">⚖️</span> Preceptoria
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col">

            <!-- NAVBAR -->
            <header class="h-16 bg-white border-b flex items-center justify-between px-8 shadow-sm">
                <div class="text-sm text-slate-500">Bem-vindo, <span class="font-semibold text-slate-800">Professor de
                        Latim</span></div>
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 rounded-full bg-navy flex items-center justify-center text-gold text-xs font-bold">
                        PL</div>
                </div>
            </header>

            <!-- DASHBOARD CONTENT -->
            <div class="p-8">
                <h2 class="font-classic text-2xl font-bold text-navy mb-6 uppercase tracking-wider">Panorama Acadêmico
                </h2>

                <!-- KPI CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-gold">
                        <p class="text-xs text-slate-500 uppercase font-bold">Total Alunos</p>
                        <p class="text-3xl font-semibold">124</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-navy">
                        <p class="text-xs text-slate-500 uppercase font-bold">Média Global</p>
                        <p class="text-3xl font-semibold text-navy">B</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-gold">
                        <p class="text-xs text-slate-500 uppercase font-bold">Avaliações/Bim</p>
                        <p class="text-3xl font-semibold">12</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-navy">
                        <p class="text-xs text-slate-500 uppercase font-bold">Preceptorias</p>
                        <p class="text-3xl font-semibold">88%</p>
                    </div>
                </div>

                <!-- TABLES / RECENT ACTIVITY -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="font-semibold text-lg text-navy">Lançamentos Recentes</h3>
                        <button
                            class="text-sm bg-navy text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">Nova
                            Avaliação</button>
                    </div>
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-400 text-xs uppercase font-bold">
                            <tr>
                                <th class="px-6 py-4">Aluno</th>
                                <th class="px-6 py-4">Disciplina</th>
                                <th class="px-6 py-4">Avaliação</th>
                                <th class="px-6 py-4 text-center">Nota</th>
                                <th class="px-6 py-4 text-center">Conceito</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <!-- Dado Fictício 1 -->
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium">Augusto de Hipona</td>
                                <td class="px-6 py-4">Latim</td>
                                <td class="px-6 py-4">Declinações I</td>
                                <td class="px-6 py-4 text-center">9.5</td>
                                <td class="px-6 py-4 text-center"><span
                                        class="px-2 py-1 bg-green-100 text-green-700 rounded-md text-xs font-bold">A</span>
                                </td>
                            </tr>
                            <!-- Dado Fictício 2 -->
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium">Tomás de Aquino</td>
                                <td class="px-6 py-4">Filosofia</td>
                                <td class="px-6 py-4">Lógica Aristotélica</td>
                                <td class="px-6 py-4 text-center">8.2</td>
                                <td class="px-6 py-4 text-center"><span
                                        class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-bold">B</span>
                                </td>
                            </tr>
                            <!-- Dado Fictício 3 -->
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium">Bento de Núrsia</td>
                                <td class="px-6 py-4">Religião</td>
                                <td class="px-6 py-4">Vida Monástica</td>
                                <td class="px-6 py-4 text-center">7.0</td>
                                <td class="px-6 py-4 text-center"><span
                                        class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-md text-xs font-bold">C</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>

</html>
