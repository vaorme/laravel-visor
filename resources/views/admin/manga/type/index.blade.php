<x-admin-layout>

    hola desde index manga demography
    <a href="{{ route('manga_types.create') }}">Crear tipo</a>
    
    @if ($types)
        <ul>
            @foreach ($types as $type)
                <li>{{ $type->name }}</li>
            @endforeach
        </ul>
    @else
        <div class="no_results">
            Sin resultados
        </div>
    @endif

</x-admin-layout>