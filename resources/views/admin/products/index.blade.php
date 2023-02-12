<x-admin-layout>

    hola desde Products
    <a href="{{ route('products.create') }}">Crear producto</a>
    <hr>
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