<x-admin-layout>

    hola desde manga type
    <a href="{{ route('manga_types.create') }}">Crear tipo</a>
    
    @if ($loop->isNotEmpty())
        <ul>
            @foreach ($loop as $t)
                <li>{{ $t->name }}</li>
            @endforeach
        </ul>
    @else
        <div class="no_results">
            Sin resultados
        </div>
    @endif

</x-admin-layout>