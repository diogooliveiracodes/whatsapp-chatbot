@if (session('error'))
    <x-global.alert type="error">
        {{ session('error') }}
    </x-global.alert>
@endif

@if (session('success'))
    <x-global.alert type="success">
        {{ session('success') }}
    </x-global.alert>
@endif
