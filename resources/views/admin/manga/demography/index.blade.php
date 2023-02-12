<x-admin-layout>

    hola desde index manga demography
    <a href="{{ route('manga_demography.create') }}">Crear demografia</a>
    
    @if ($loop->isNotEmpty())
        <ul>
            @foreach ($loop as $d)
                <li>{{ $d->name }}</li>
            @endforeach
        </ul>
    @else
        <div class="no_results">
            Sin resultados
        </div>
    @endif

</x-admin-layout>