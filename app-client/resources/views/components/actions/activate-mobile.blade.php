@props(['route', 'confirmMessage'])

<form action="{{ $route }}" method="POST" class="inline">
    @csrf
    @method('PATCH')
    <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('{{ $confirmMessage }}')">
        {{ $slot }}
    </button>
</form>
