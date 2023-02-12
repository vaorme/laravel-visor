<x-admin-layout>

    hola desde Product type
    <a href="{{ route('product_types.create') }}">Crear tipo</a>
    
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