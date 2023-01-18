<x-admin-layout>

    hola desde index manga demography
    <a href="{{ route('manga_demography.create') }}">Crear demografia</a>
    
    @if ($demographics)
        <ul>
            @foreach ($demographics as $demo)
                <li>{{ $demo->name }}</li>
            @endforeach
        </ul>
    @else
        <div class="no_results">
            Sin resultados
        </div>
    @endif

</x-admin-layout>