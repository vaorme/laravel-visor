<x-admin-layout>
    logueado
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">Salir</button>
    </form>
</x-admin-layout>