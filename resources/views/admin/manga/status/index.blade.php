<x-admin-layout>

    hola desde index manga status
    <a href="{{ route('manga_book_status.create') }}">Crear status</a>
    
    @if ($loop->isNotEmpty())
        <ul>
            @foreach ($loop as $s)
                <li>{{ $s->name }}</li>
            @endforeach
        </ul>
    @else
        <div class="no_results">
            Sin resultados
        </div>
    @endif

</x-admin-layout>