<x-admin-layout>

    hola desde index Permisos
    <a href="{{ route('permissions.create') }}">Crear</a>
    
    @if ($loop)
        <ul>
            @foreach ($loop as $p)
                <li>{{ $p->name }}</li>
            @endforeach
        </ul>
    @else
        <div class="no_results">
            Sin resultados
        </div>
    @endif

</x-admin-layout>