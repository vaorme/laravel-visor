<x-admin-layout>

    hola desde index manga
    <a href="{{ route('manga.create') }}">Crear manga</a>
    
    @if ($mangas['data'])
        <ul>
            @foreach ($mangas['data'] as $manga)
                <li>{{ $manga['name'] }}</li>
            @endforeach
        </ul>
    @else
        <div class="no_results">
            Sin resultados
        </div>
    @endif

</x-admin-layout>