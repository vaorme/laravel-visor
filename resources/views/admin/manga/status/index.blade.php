<x-admin-layout>

    hola desde index manga status
    <a href="{{ route('manga_book_status.create') }}">Crear status</a>
    
    @if ($status)
        <ul>
            @foreach ($status as $item)
                <li>{{ $item->name }}</li>
            @endforeach
        </ul>
    @else
        <div class="no_results">
            Sin resultados
        </div>
    @endif

</x-admin-layout>