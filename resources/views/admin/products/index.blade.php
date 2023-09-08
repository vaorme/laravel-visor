<x-admin-layout>
    <x-slot:title>Manga</x-slot>
    @php
        $status = request()->all('status');
    @endphp
    <x-admin.nav>
        <li class="{{ (request()->is('controller/manga/type')) ? 'active' : '' }}"><a href="{{ route('manga_types.index') }}">Tipos</a></li>
        <li class="{{ (request()->is('controller/manga/status')) ? 'active' : '' }}"><a href="{{ route('manga_book_status.index') }}">Estados</a></li>
        <li class="{{ (request()->is('controller/manga/demography')) ? 'active' : '' }}"><a href="{{ route('manga_demography.index') }}">Demografia</a></li>
    </x-admin.nav>
    <x-admin.bar title="Productos" :buttonTo="route('products.create')" buttonText="Añadir producto"/>
    <div class="contain">
        @if (Session::has('success'))
                <div class="alertas success">
                    <div class="box">
                        <p>{!! \Session::get('success') !!}</p>
                    </div>
                </div>
                <script>
                    let alerta = document.querySelector('.alertas');
                    setTimeout(() => {
                        alerta.remove();
                    }, 2000);
                </script>
            @endif
            @if (Session::has('error'))
                <div class="alertas error">
                    <div class="box">
                        <p>{!! \Session::get('error') !!}</p>
                    </div>
                </div>
                <script>
                    let alerta = document.querySelector('.alertas');
                    setTimeout(() => {
                        alerta.remove();
                    }, 2000);
                </script>
            @endif
        @if ($loop->isNotEmpty())
            @php
                $other = $loop->links('vendor.pagination.default');
            @endphp
            <div class="table">
                <x-admin.table-search />
                <table id="tablr">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loop as $i)
                                <td>{{ $i->name }}</td>
                                <td></td>
                                <td>
                                    
                                </td>
                                <td>
                                    <div class="buttons">
                                        @can('products.destroy')
                                            <a href="{{ route('products.destroy', ['id' => $i->id]) }}" class="productDelete" data-id="{{ $i->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <line x1="4" y1="7" x2="20" y2="7" />
                                                    <line x1="10" y1="11" x2="10" y2="17" />
                                                    <line x1="14" y1="11" x2="14" y2="17" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </a>
                                        @endcan
                                        @can('products.edit')
                                            <a href="{{ route('products.edit', ['id' => $i->id]) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                    <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                                </svg>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $other }}
            </div>
        @else
            <div class="empty">
                <p>No hay resultados</p>
            </div>
        @endif
    </div>

</x-admin-layout>