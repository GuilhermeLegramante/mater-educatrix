@if (session('success'))
    <x-alert type="success">{{ session('success') }}</x-alert>
@endif

@if (session('error') || $errors->any())
    <x-alert type="error">
        {{ session('error') ?? 'Verifique os dados introduzidos nos campos abaixo.' }}
    </x-alert>
@endif
