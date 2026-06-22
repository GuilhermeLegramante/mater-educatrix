<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mater Educatrix - Portal Acadêmico</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-950 text-slate-200 font-sans antialiased min-h-screen">

    <main class="min-h-screen">
        @yield('content')
    </main>

</body>

</html>
