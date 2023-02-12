<x-admin-layout>

    hola desde index Roles
    <a href="{{ route('roles.create') }}">Crear</a>
    
    @if ($loop->isNotEmpty())
        <ul>
            @foreach ($loop as $item)
                <li>{{ $item->name }}</li>
            @endforeach
        </ul>
    @else
        <div class="no_results">
            Sin resultados
        </div>
    @endif

</x-admin-layout>