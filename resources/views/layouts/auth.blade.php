<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mater Educatrix - Portal Acadêmico</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="/img/icone.png">

    <link rel="manifest" href="{{ asset('manifest.json') }}" crossorigin="use-credentials">
</head>

<body class="bg-slate-950 text-slate-200 font-sans antialiased min-h-screen">

    <main class="min-h-screen">
        @yield('content')
    </main>

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
