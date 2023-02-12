<x-admin-layout>

    hola desde rangos
    <a href="{{ route('ranks.create') }}">Crear rango</a>
    
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